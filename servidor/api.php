<?php
/*
  Ezchema · guardado privado de proyectos en tu servidor
  Copyright (C) 2026 Héctor Cruz Juárez · GPL-3.0-only
  Recibe peticiones POST en JSON desde Ezchema. Todas requieren la contraseña (encabezado X-Ezchema-Key).
  Acciones: ping, list, save, load, delete.
*/
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function out(array $data, int $code = 200): void { http_response_code($code); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }

$cfgFile = __DIR__ . '/config.php';
if (!is_file($cfgFile)) out(['ok' => false, 'error' => 'setup'], 503);
$cfg = require $cfgFile;
$maxMb = (int)($cfg['max_mb'] ?? 25);
$keepBackups = (int)($cfg['backups'] ?? 20);

// Solo se aceptan peticiones de otros dominios si están en la lista de config.php
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && in_array($origin, $cfg['origins'] ?? [], true)) {
  header('Access-Control-Allow-Origin: ' . $origin);
  header('Vary: Origin');
  header('Access-Control-Allow-Headers: Content-Type, X-Ezchema-Key');
  header('Access-Control-Allow-Methods: POST, OPTIONS');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') exit;
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(['ok' => false, 'error' => 'method'], 405);

$dir = __DIR__ . '/datos';
foreach (["$dir/proyectos", "$dir/respaldos"] as $d) if (!is_dir($d)) @mkdir($d, 0750, true);

// Freno a intentos fallidos: 8 errores por IP en 15 minutos bloquean temporalmente
$ip = $_SERVER['REMOTE_ADDR'] ?? 'x';
$failFile = "$dir/.intentos.json";
$fails = is_file($failFile) ? (json_decode((string)file_get_contents($failFile), true) ?: []) : [];
$now = time();
$fails = array_filter($fails, fn($t) => is_array($t) && ($now - ($t['t'] ?? 0)) < 900);
$mine = $fails[$ip] ?? ['n' => 0, 't' => $now];
if (($mine['n'] ?? 0) >= 8) out(['ok' => false, 'error' => 'blocked'], 429);

$raw = file_get_contents('php://input', false, null, 0, $maxMb * 1048576 + 1);
if ($raw === false || strlen($raw) > $maxMb * 1048576) out(['ok' => false, 'error' => 'too_large'], 413);
$req = json_decode($raw, true);
if (!is_array($req)) out(['ok' => false, 'error' => 'bad_request'], 400);

$key = (string)($_SERVER['HTTP_X_EZCHEMA_KEY'] ?? ($req['key'] ?? ''));
if ($key === '' || !password_verify($key, (string)($cfg['hash'] ?? ''))) {
  $fails[$ip] = ['n' => ($mine['n'] ?? 0) + 1, 't' => $now];
  @file_put_contents($failFile, json_encode($fails), LOCK_EX);
  usleep(800000);
  out(['ok' => false, 'error' => 'auth'], 401);
}
if (isset($fails[$ip])) { unset($fails[$ip]); @file_put_contents($failFile, json_encode($fails), LOCK_EX); }

function cut($s, int $n): string {
  $s = (string)$s;
  return function_exists('mb_substr') ? mb_substr($s, 0, $n) : (preg_match('/^.{0,' . $n . '}/us', $s, $m) ? $m[0] : substr($s, 0, $n));
}
function validId($id): string {
  $id = (string)$id;
  if (!preg_match('/^[a-z0-9][a-z0-9-]{0,90}$/', $id)) out(['ok' => false, 'error' => 'bad_id'], 400);
  return $id;
}
function writeAtomic(string $file, string $data): void {
  $tmp = $file . '.tmp' . bin2hex(random_bytes(4));
  if (file_put_contents($tmp, $data, LOCK_EX) === false || !rename($tmp, $file)) { @unlink($tmp); out(['ok' => false, 'error' => 'write'], 500); }
}
function backup(string $dir, string $id, int $keep): void {
  $src = "$dir/proyectos/$id.json";
  if (!is_file($src)) return;
  @copy($src, "$dir/respaldos/$id-" . date('Ymd-His') . '.json');
  $old = glob("$dir/respaldos/$id-*.json") ?: [];
  rsort($old);
  foreach (array_slice($old, $keep) as $f) @unlink($f);
}

$action = (string)($req['action'] ?? '');
switch ($action) {
  case 'ping':
    out(['ok' => true, 'server' => 'ezchema', 'max_mb' => $maxMb]);

  case 'list':
    $items = [];
    foreach (glob("$dir/proyectos/*.meta.json") ?: [] as $m) {
      $meta = json_decode((string)file_get_contents($m), true);
      if (is_array($meta)) $items[] = $meta;
    }
    usort($items, fn($a, $b) => strcmp((string)($b['saved'] ?? ''), (string)($a['saved'] ?? '')));
    out(['ok' => true, 'items' => $items]);

  case 'save':
    $id = validId($req['id'] ?? '');
    $doc = $req['doc'] ?? null;
    if (!is_array($doc) || !isset($doc['nodes']) || !is_array($doc['nodes'])) out(['ok' => false, 'error' => 'bad_doc'], 400);
    $json = json_encode(['app' => 'ezchema', 'version' => 2, 'doc' => $doc], JSON_UNESCAPED_UNICODE);
    if ($json === false) out(['ok' => false, 'error' => 'bad_doc'], 400);
    backup($dir, $id, $keepBackups);
    writeAtomic("$dir/proyectos/$id.json", $json);
    $meta = ['id' => $id, 'name' => cut($req['name'] ?? $id, 160), 'version' => cut($req['ver'] ?? '', 40),
             'app' => cut($req['app'] ?? '', 20), 'saved' => date('c'), 'size' => strlen($json)];
    writeAtomic("$dir/proyectos/$id.meta.json", json_encode($meta, JSON_UNESCAPED_UNICODE));
    out(['ok' => true, 'item' => $meta]);

  case 'load':
    $id = validId($req['id'] ?? '');
    $f = "$dir/proyectos/$id.json";
    if (!is_file($f)) out(['ok' => false, 'error' => 'not_found'], 404);
    $data = json_decode((string)file_get_contents($f), true);
    out(['ok' => true, 'doc' => $data['doc'] ?? $data]);

  case 'delete':
    $id = validId($req['id'] ?? '');
    backup($dir, $id, $keepBackups + 1);
    @unlink("$dir/proyectos/$id.json");
    @unlink("$dir/proyectos/$id.meta.json");
    out(['ok' => true]);

  default:
    out(['ok' => false, 'error' => 'action'], 400);
}

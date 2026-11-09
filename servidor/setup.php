<?php
/*
  Ezchema · configuración inicial del guardado privado (se usa una sola vez).
  Crea config.php con la contraseña cifrada. Después de usarlo, borra este archivo del servidor.
*/
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
$cfgFile = __DIR__ . '/config.php';
$done = is_file($cfgFile);
$msg = '';
if (!$done && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $p1 = (string)($_POST['p1'] ?? ''); $p2 = (string)($_POST['p2'] ?? '');
  $origins = array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['origins'] ?? '')))));
  if (strlen($p1) < 10) $msg = 'La contraseña debe tener al menos 10 caracteres.';
  elseif ($p1 !== $p2) $msg = 'Las contraseñas no coinciden.';
  else {
    $cfg = ['hash' => password_hash($p1, PASSWORD_DEFAULT), 'origins' => $origins, 'max_mb' => 25, 'backups' => 20];
    $php = "<?php\n// Ezchema: configuración del guardado privado. Generado por setup.php el " . date('Y-m-d H:i') . "\nreturn " . var_export($cfg, true) . ";\n";
    if (@file_put_contents($cfgFile, $php, LOCK_EX) === false) $msg = 'No se pudo escribir config.php. Revisa los permisos de la carpeta.';
    else {
      @mkdir(__DIR__ . '/datos/proyectos', 0750, true); @mkdir(__DIR__ . '/datos/respaldos', 0750, true);
      $done = true; $msg = 'Listo. La contraseña quedó guardada (cifrada) en config.php.';
    }
  }
}
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Ezchema · configurar servidor</title>
<style>body{font-family:Arial,Helvetica,sans-serif;max-width:560px;margin:40px auto;padding:0 18px;color:#141517;line-height:1.5}h1{font-family:'Courier New',monospace;font-size:22px}label{display:block;margin:14px 0 4px;font-size:14px;color:#5d636b}input,textarea{width:100%;box-sizing:border-box;padding:8px;border:1px solid #d3d5d9;border-radius:4px;font:inherit}button{margin-top:16px;padding:9px 16px;border:0;border-radius:4px;background:#141517;color:#fff;font:inherit;cursor:pointer}.msg{padding:10px 12px;border-radius:4px;background:#f3f4f5;margin:14px 0}.warn{background:#fdf1dc}</style></head><body>
<h1>EZCHEMA · servidor</h1>
<?php if ($msg): ?><p class="msg"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if ($done): ?>
  <p>El guardado privado ya está configurado.</p>
  <p class="msg warn"><b>Importante:</b> borra ahora el archivo <code>setup.php</code> del servidor (desde el Administrador de archivos de cPanel). Si necesitas cambiar la contraseña, borra <code>config.php</code>, vuelve a subir <code>setup.php</code> y repite.</p>
<?php else: ?>
  <p>Elige la contraseña con la que Ezchema guardará y abrirá proyectos en este servidor. Se guarda cifrada; nadie puede leerla desde el servidor.</p>
  <form method="post" autocomplete="off">
    <label for="p1">Contraseña (mínimo 10 caracteres)</label><input id="p1" name="p1" type="password" required minlength="10">
    <label for="p2">Repite la contraseña</label><input id="p2" name="p2" type="password" required minlength="10">
    <label for="o">Otros dominios desde donde usarás Ezchema (opcional, uno por línea, por ejemplo https://otrodominio.com). Si Ezchema está en este mismo sitio, déjalo vacío.</label><textarea id="o" name="origins" rows="3"></textarea>
    <button type="submit">Guardar configuración</button>
  </form>
<?php endif; ?>
</body></html>

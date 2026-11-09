# Ezchema · Formato de proyecto para generar borradores

**Para:** el skill que convierte el análisis de un texto (obra, guion, dramaturgia) en un proyecto multimedia de Ezchema.
**Versión de Ezchema:** v0.40 · Formato de documento `v: 4` · Colección Alchemy of Meaning (Alquimia del Significado), hectorcruz.com.mx.

## 0. Cambios para el skill desde la versión anterior de este documento

1. **Hojas verticales por defecto.** `paper.orient` y cada hoja van en `"v"` (carta vertical, 816 × 1056 px). Solo usa `"h"` si Héctor pide horizontal.
2. **Caracteres seguros para el PDF.** No uses flechas (→), ≈, ≥, ✓ ni emoji en textos; usa `·`, `–` o `»` (ver 2.1). Ezchema v0.39 ya los cambia solo, pero conviene escribirlos limpios.
3. **Descripción del proyecto:** un solo párrafo, 600–1500 caracteres. La lista técnica la muestra completa hasta unas 24 líneas.
4. **Acomodo actualizado** para hojas verticales (3.2) y ejemplo nuevo (11).

## 1. Qué produce el skill

Un archivo **`.json`** que Ezchema abre con **Abrir**. También sirve para el servidor privado, que guarda el mismo objeto dentro de `{"doc": …}`. Ezchema acepta el documento solo o envuelto en `doc`.

Un borrador avanzado debe traer, en un mismo archivo:

1. **Diagrama de señal.** Tarjetas de equipo con puertos y cables: qué se conecta con qué, con qué cable y de qué largo.
2. **Planta a escala.** Escenario, superficies y muros, proyectores en planta, luminarias, pantallas emisoras y figuras de escala. Esto alimenta la **Previz** (3D).
3. **Hojas** tamaño carta que encuadran el diagrama y la planta para exportar el PDF.
4. **Datos del proyecto.** Nombre, compañía, foro, fecha y descripción.

Ezchema calcula solo la lista técnica, la revisión con avisos (cables largos, imagen que no cubre la superficie, patch DMX), lux, tamaños de imagen, glosario y PDF. El skill **no** debe generar esos datos: basta con describir bien los elementos.

## 2. Reglas de oro

1. `nodes` y `cables` son obligatorios (arreglos). Sin ellos Ezchema rechaza el archivo.
2. Cada nodo lleva un `id` único (texto libre corto, por ejemplo `n-proy1`). Los cables apuntan a esos `id`.
3. Copia la **plantilla completa** de la clase (sección 5 y apéndice B) y cambia solo lo necesario. Ezchema **no** completa campos de dibujo faltantes. Una figura sin `w`, `h`, `fill`… se dibuja mal.
4. Los puertos son **texto** dentro de `ins` y `outs`. Los cables usan el **índice** del puerto en ese arreglo.
5. Usa `"v": 4`. Con `v` menor a 4, Ezchema borra los puertos sin cable; con `v` menor a 3, además reacomoda las tarjetas (ver 3.3).
6. Distancias y alturas de la Previz en **metros**. Posiciones `x`, `y` en **píxeles del lienzo** (ver 3).
7. Usa claves `key` del catálogo (apéndice A). Si un equipo no existe, usa un genérico (`generic`, `gen-src`, `gen-proj`, `gen-disp`, `gen-cam`, `gen-ctl`, `gen-net`, `gen-audio`, `gen-pwr`, `gen-surf`, `gen-sw`, `gen-rig`, `gen-person`) con `label` y `model` descriptivos.
8. Textos en español de México. Ezchema traduce su interfaz al inglés, pero los textos del proyecto quedan como se escriben.
9. **Hojas en vertical** (`"orient": "v"`) salvo que se pida horizontal.
10. **Solo caracteres seguros para el PDF** en todos los textos (2.1).

### 2.1 Texto y caracteres

Las fuentes del PDF (Helvetica, Times y Courier) solo tienen el juego Windows-1252. Un carácter fuera de él descompone la línea completa en el PDF: espaciado roto y signos como «!'». Desde v0.39 Ezchema los reemplaza al exportar, pero el skill debe escribirlos ya limpios:

| Evitar | Usar |
|---|---|
| → ⇒ ➜ | `·` para separar («Prólogo · loop de pensamientos · feedback»), o `»` |
| ← ↔ | `«`, o redactar |
| ≈ ≥ ≤ ≠ | ~, >=, <=, !=, o con palabras («unos 3 m») |
| ✓ ✔ ✗ | «sí», «no», «ok» |
| Emoji y símbolos (🎥 ★ ●) | Quitarlos |
| Espacios especiales | Espacio normal |

Se pueden usar sin problema: acentos, ñ, ü, ¿ ¡, comillas “ ” ‘ ’, `·` `–` `—` `•` `…` `×` `°` `«` `»`.

**Notas (`note.text`):** líneas cortas. Para listas de escenas, una viñeta por momento: `- Prólogo · loop de pensamientos · feedback y delay, TV`. La alineación es a la izquierda (`"align": "left"`); el ancho `w` de 300–520 px se lee bien en una hoja carta vertical.

**Descripción (`project.descripcion`):** un párrafo sin saltos de línea, de 600 a 1500 caracteres. Debe decir qué es el dispositivo, cómo se opera y qué debe contemplar el foro.

## 3. Coordenadas, escala y acomodo

### 3.1 El lienzo
- Un solo lienzo infinito en píxeles. `x` crece a la derecha y `y` hacia abajo.
- **Tarjetas de equipo** (diagrama de señal): miden **190 px** de ancho (`W`) y unos 60–110 px de alto según sus puertos. El ancho cambia con `cardScale` (1, 0.75 o 0.6). Las tarjetas no van a escala.
- **Elementos de planta** (`shape`, `beamer`, `lum`, `disp`, `fig`): van **a escala**.
  - `doc.scale` = píxeles por metro. 50 (inicial) equivale a 1:76 en papel.
  - Otras escalas: 75.59 = 1:50 · 50.39 = 1:75 · 37.8 = 1:100 · 25.2 = 1:150 · 18.9 = 1:200.
  - `metros × scale = píxeles`.
- **Hojas:** 1 mm de papel = 3.78 px.
  - Carta vertical = 816 × 1056 px.
  - Carta horizontal = 1056 × 816 px.
  - El cuadro de datos ocupa unos 60 px abajo y los márgenes unos 57 px (15 mm).

### 3.2 Acomodo recomendado (dos hojas carta verticales)

| Zona | Hoja | Contenido | Posición sugerida |
|---|---|---|---|
| Diagrama de señal | `pg1` en (-40, -60), 816 × 1056 px | Nota de título y tarjetas en dos columnas | Columnas en x = 20 y x = 380; filas desde y = 120 cada 140 px, hasta y ≈ 860 |
| Planta | `pg2` en (900, -60), 816 × 1056 px | Escenario, muros, proyectores, luminarias, pantallas, figuras | Escenario de 10 × 8 m (500 × 400 px) en (1050, 40); proyector abajo, hacia y ≈ 700 |

El área útil de una hoja vertical va de x + 57 a x + 759 y de y + 57 a y + 990 (el cuadro de datos ocupa la parte baja).

Acomodo de las tarjetas, por filas de arriba abajo (son los mismos niveles que usa Ezchema):

| Fila | Contenido |
|---|---|
| 0 | Superficies y pantallas |
| 1 | Proyectores, cámaras y montaje |
| 2 | Procesamiento, red, conversores y energía |
| 3 | Computadoras y software |
| 4 | Personas y operación |

Con dos columnas de 190 px caben unas 12 tarjetas por hoja. Si hay más, agrega una hoja de señal más abajo, por ejemplo `pg3` en (-40, 1060), o usa `cardScale: 0.75`. Lo que queda dentro del rectángulo de una hoja se exporta en esa página.

**Orientación en la planta:** el público está abajo (+y) y el fondo del escenario arriba (−y). Un proyector con `ang: 0` proyecta hacia arriba, al escenario. Una pantalla o figura con `ang: 0` mira hacia abajo, al público. `ang` está en grados, en sentido horario.

### 3.3 Acomodo automático (opcional)
Con `"v": 2`, Ezchema al abrir:
- acomoda las tarjetas por esas filas;
- quita los puertos sin cable;
- pone el fondo claro.

Los elementos de planta no se mueven. Sirve si el skill no calcula posiciones, pero entonces las tarjetas pueden quedar fuera de las hojas. Para un borrador listo para PDF, usa `v: 4` con posiciones explícitas.

## 4. Documento raíz

| Campo | Tipo | Valor recomendado | Notas |
|---|---|---|---|
| `v` | número | `4` | Versión del formato |
| `project` | objeto | ver abajo | Datos del cuadro de las hojas y de la lista |
| `theme` | texto | `"claro"` | `claro`, `blanco` u `oscuro` |
| `scale` | número | `50` | Píxeles por metro de la planta |
| `cardScale` | número | `1` | 1, 0.75 o 0.6 |
| `paper` | objeto | `{"size":"carta","orient":"v"}` | Formato de las hojas nuevas. `size`: carta, a4, tabloide, a3. `orient`: v o h |
| `legend` | bool | `true` | Leyenda de tipos de cable al exportar |
| `glossary` | bool | omitir (o `true`) | `false` quita el glosario de la lista técnica (PDF y Word) |
| `layers` | arreglo | `[{"id":"L1","name":"General","vis":true,"lock":false,"op":100}]` | Capas; se ordenan de abajo arriba |
| `activeLayer` | texto | `"L1"` | |
| `versions` | arreglo | `[]` | Bitácora de versiones (déjala vacía) |
| `pages` | arreglo | ver 7 | Hojas |
| `nodes` | arreglo | ver 5 | **Obligatorio** |
| `cables` | arreglo | ver 6 | **Obligatorio** |
| `hideBeams` | bool | omitir | Oculta los haces de luz en la planta |
| `v3` | objeto | omitir | Ajustes de la Previz (estilo, cámara, luz); Ezchema los crea |

**`project`:**
```json
{"nombre":"", "compania":"", "disenador":"Héctor Cruz Juárez", "venue":"", "fecha":"AAAA-MM-DD", "version":"borrador 1", "contacto":"", "descripcion":"", "logo":""}
```
`descripcion` aparece en la lista técnica y en el PDF; conviene un párrafo claro sobre qué es el dispositivo, cómo se opera y qué debe contemplar el teatro. `logo` es un dataURL de imagen o vacío.

## 5. Nodos

### 5.1 Campos comunes (todos los nodos)
| Campo | Tipo | Notas |
|---|---|---|
| `id` | texto | Único |
| `key` | texto | Clave del catálogo (apéndice A) |
| `cls` | texto | `device`, `surface`, `software`, `rigging`, `person`, `note`, `shape`, `beamer`, `lum`, `disp`, `fig` o `image` |
| `t` | texto | Ícono. Copia el de la clave en el apéndice A |
| `x`, `y` | número | Posición en px (esquina superior izquierda en tarjetas, notas y formas; centro en `beamer`, `lum`, `disp` y `fig`) |
| `label` | texto | Nombre visible |
| `model` | texto | Marca y modelo, o versión del software |
| `qty` | número | Cantidad (1 por defecto) |
| `provee` | texto | `""`, `"Producción"`, `"Teatro / venue"`, `"Renta"` o `"Por definir"` |
| `notes` | texto | Nota para la lista técnica |
| `url` | texto | Enlace (ficha técnica, tienda) |
| `ins`, `outs` | arreglo de texto | Puertos (ver 6) |
| `layer` | texto | Id de capa (`"L1"`) |
| `noList` | bool | `true` para no contarlo en la lista técnica |
| `locked`, `hidden`, `op` | | Bloqueado, oculto, opacidad 0–100 (opcionales) |

### 5.2 Tarjetas del diagrama de señal (`device`, `surface`, `software`, `rigging`, `person`)
- **`device`**: equipo con puertos. Los proyectores (`t: "projector"`) llevan además `lens` (`std`, `short`, `ust`, `long` o `zoom`), `trMin`, `trMax` (throw ratio) y `lm` (lúmenes).
- **`surface`**: superficie de proyección del diagrama, con `mat` (material), `sw` y `sh` (ancho y alto en m) y la entrada `"Proyección"`.
- **`software`**: licencia; no lleva puertos.
- **`rigging`**: soporte o montaje; va a la sección Montaje y rigging de la lista.
- **`person`**: rol del equipo humano (operador, técnico); va a la lista de personal.

### 5.3 Nota de texto (`note`)
`text` admite un formato mínimo:
- `# ` al inicio de línea es un título;
- `- ` es una viñeta;
- `1. ` es un número;
- una línea vacía separa párrafos.

`w` es el ancho en px y `fs` el tamaño de letra. La variante `note-call` lleva `ptr` (punta de la flecha, relativa a la nota).

### 5.4 Formas de planta (`shape`)
`shape` puede ser `rect`, `ellipse`, `poly` (`sides`), `line` o `path` (`pts` relativos, `closed`). `w` y `h` van en px; usa `metros × scale`.

**Cómo se ven en la Previz** (`m3`):
| Tipo de forma | Valores de `m3` |
|---|---|
| Cerrada (rect, ellipse, poly, path cerrado) | `piso` (superficie en el piso, por defecto), `vol` (volumen: escenografía, tarima, mesa) u `off` |
| Línea o path abierto | `linea` (por defecto), `muro` (pantalla, ciclorama o pared vertical) u `off` |

- `z3`: base sobre el piso en m.
- `h3`: altura en m (volumen 1 m y muro 3 m por defecto).
- `rot`: grados de rotación para rect, ellipse y poly.
- `text`: el texto que se ve dentro; también nombra la superficie en la Previz.

Usa las formas de **Espacio escénico** y **Montaje en planta** (escenario, butacas, varas, truss, mesa) como base.

### 5.5 Proyector en planta (`beamer`, clave `beamer`)
Calcula la imagen con el throw ratio y alimenta la Previz.
- Va con `noList: true` porque normalmente la tarjeta del diagrama (`proj-*`) es la que se cuenta.
- **En planta:** `dist` (m, distancia horizontal a la imagen), `ang` (dirección), `zoom` (0–100), `lens`, `trMin`, `trMax`, `lm`, `aspect` (`16:9`, `16:10` o `4:3`).
- **En la Previz:** `h3` (altura de la lente en m), `tilt` (inclinación en grados, positivo hacia arriba), `lsv` y `lsh` (lens shift vertical y horizontal en %).
- **Contenido:** `src3` puede ser `patron`, `imagen` o `video`. `img3` es un dataURL JPG de 2048 px como máximo. El video no se guarda.

### 5.6 Luminaria (`lum`, claves `l-*`)
Va a escala en la planta, con haz y huella; en la Previz da luz real en los estilos Sala y Render.
- **Tipo:** `lt` puede ser leko, par, fres, cyc, seg o mov.
- **Óptica y potencia:** `angMin` y `angMax` (campo en grados), `zoom`, `watts`, `led`.
- **Posición:** `alt` (altura de la vara en m), `foco` (altura de enfoque en m), `dist` (distancia en planta al foco en m), `ang`.
- **Patch:** `canal`, `univ`, `addr`, `modo`. Ezchema revisa los traslapes.
- **Diseño:** `color` (filtro, por ejemplo L201), `colorHex`, `gobo`, `pos` (por ejemplo "Vara 1"), `prop` (propósito).
- **Haz y huella:** `beam: true` los dibuja.

### 5.7 Pantalla emisora en planta (`disp`, claves `d-*`)
Una TV, monitor o muro LED a escala, que emite su propia imagen en la Previz. `dt` puede ser `crt`, `flat`, `wedge`, `tablet`, `led` o `ledc`.
- **TV, monitor y tablet:** `diag` (pulgadas), `aspect` (`16:9`, `16:10`, `4:3`, `21:9` o `9:16`), `res`.
- **LED:** `cols` y `rows` (módulos), `modW` y `modH` (m), `pitch` (mm), `R` (radio de curva en m; 0 = plano).
- **Comunes:** `z3` (base en m), `ang`, `nits`, `watts`.
- **Efectos:** `fx` (líneas de barrido en CRT o retícula de píxeles en LED), `tiltd` (inclinación del monitor de piso).
- **Contenido:** igual que el proyector (`src3`, `img3`).

### 5.8 Figura de escala (`fig`, claves `f-*`)
Silueta para dar escala. No se cuenta en la lista y recibe la proyección en la Previz.
- `fk`: `mujer`, `hombre`, `nina`, `nino` o `grupo`.
- `alt`: estatura en m. Referencias para México: mujer 1.60–1.65 y hombre 1.70–1.75.
- `ang`, `z3` (por ejemplo, sobre una tarima).
- `label`: texto en la planta (por ejemplo "Actriz"). Vacío muestra la estatura.

### 5.9 Imagen (`image`)
`src` (dataURL), `w`, `h` (px), `ar` (proporción), `caption`. Casi nunca la necesitará el skill.

## 6. Puertos y cables

### 6.1 Puertos
- `ins` y `outs` son arreglos de nombres.
- En el catálogo las tarjetas vienen **vacías**: los puertos nacen al conectar. Las columnas de entradas y salidas sugeridas del apéndice A son los nombres que ofrece Ezchema.
- Escribe en el nodo solo los puertos que uses, con esos nombres o con nombres claros ("HDMI 2", "Video compuesto (RCA)").
- **El nombre decide el tipo de cable**, su color y la revisión:

| Si el nombre contiene… | Tipo | Nombre en la lista |
|---|---|---|
| COMPUESTO, COMPOSITE, CVBS, S-VIDEO, RCA VIDEO | `ana` | Video análogo (RCA, BNC) |
| HDMI | `hdmi` | HDMI |
| SDI, BNC | `sdi` | SDI / BNC |
| HDBT, HDBASET | `hdbt` | HDBaseT |
| DP, DISPLAYPORT | `dp` | DisplayPort |
| WI-FI, AIRPLAY, CHROMECAST | `wifi` | Wi-Fi |
| LAN, ETHERNET, POE, NDI, DANTE, RJ45, ART-NET, OSC | `lan` | Red |
| USB, THUNDERBOLT, LIGHTNING | `usb` | USB |
| MIDI, DMX | `ctl` | Control |
| CORRIENTE, CONTACTO, CLAVIJA, ELÉCTRIC, AC, POWER, "12 V" | `pwr` | Corriente eléctrica |
| XLR, MIC, LÍNEA, MAIN, AUDIO, L/R, JACK, RCA | `audio` | Audio |
| DVI, VGA | `dvi` | DVI / VGA |
| LENTE, HAZ, PROYECCIÓN, LENS, BEAM | `beam` | Haz de proyección |
| (otro) | `gen` | Genérico |

La regla va en ese orden y gana la primera coincidencia. Ojo: "Video compuesto BNC" es análogo, pero "BNC" solo es SDI.

### 6.2 Cable
```json
{"id":"c1", "a":{"n":"<id origen>", "s":"out", "i":0}, "b":{"n":"<id destino>", "s":"in", "i":0}, "label":"", "len":15, "provee":"Producción"}
```
| Campo | Notas |
|---|---|
| `a` | Siempre una salida: `s: "out"` e `i` = índice en `outs` del nodo origen |
| `b` | Siempre una entrada: `s: "in"` e `i` = índice en `ins` del nodo destino |
| `len` | Metros (número) o `""` si se desconoce. La lista suma los metros por tipo y avisa: HDMI más de 15 m, USB más de 5, DisplayPort más de 10, video compuesto más de 30, LAN y HDBaseT más de 100 |
| `label` | Texto sobre el cable (por ejemplo "RCA") |
| `provee` | Igual que en los nodos |
| `kind` | Opcional, para forzar el tipo (`hdmi`, `ana`, `pwr`…) |
| `noList` | Opcional; no cuenta el cable |

**Haz de proyección:** un cable de la salida `"Lente"` de un proyector (`proj-*`) a la entrada `"Proyección"` de una superficie (`surface`), con `dist` (m, distancia de tiro). Ezchema calcula el tamaño de imagen, los lux y si cubre la superficie (`sw`). No cuenta como cable en la lista.

**Corriente:** de la salida `"Contacto N"` de una toma, extensión o multicontacto a una entrada de corriente del equipo (agrega al equipo un puerto `"Corriente"` o `"Clavija"`).

## 7. Hojas (`pages`)
```json
{"id":"pg1", "size":"carta", "orient":"v", "title":"Hoja 1 · Señal", "x":-40, "y":-60}
```
- `x` e `y` son la esquina superior izquierda de la hoja en el lienzo.
- Tamaño: carta vertical = 816 × 1056 px (la predeterminada); carta horizontal = 1056 × 816 px, solo si se pide.
- Una hoja con `skip: true` no se exporta.
- El PDF de hojas exporta, en orden, lo que cae dentro de cada una, con el cuadro de datos del proyecto.

## 8. Pantalla en planta ↔ tarjeta del diagrama

Para que una TV o un LED aparezca en la Previz **y** tenga puertos en el diagrama de señal, se crean dos nodos vinculados:

| Pieza | Qué lleva | Cómo se marca |
|---|---|---|
| Tarjeta del diagrama (por ejemplo `tv-crt`, `tv`, `monitor`, `ledwall`) | Los puertos y los cables | `"link3": "<id de la pantalla>"` |
| Pantalla en planta (`d-*`) | El tamaño, la posición y la imagen | `"link": "<id de la tarjeta>"`, `"noList": true` |

La lista técnica cuenta **la tarjeta una sola vez**, con el tamaño, la resolución, los nits y el consumo de la pantalla.

Correspondencia de tipos:

| Tipo de pantalla (`dt`) | Tarjeta |
|---|---|
| crt | `tv-crt` |
| flat | `tv` |
| wedge | `monitor` |
| tablet | `gen-disp` |
| led y ledc | `ledwall` |

El mismo patrón conceptual (tarjeta que cuenta y pieza de planta con `noList`) vale para proyectores: tarjeta `proj-*` en el diagrama y `beamer` con `noList: true` en la planta, con los mismos `lm`, `trMin`, `trMax` y `label`. Este par no se vincula con un campo; solo comparten datos.

## 9. Del análisis del texto a los elementos (guía para el skill)

| En el texto o el concepto | Elementos sugeridos |
|---|---|
| Imagen proyectada sobre un fondo, ciclorama o pantalla | Tarjetas `proj-*` + `surface` (`cyc`, `front`, `rear`, `gasa`) con cable de haz. En planta: `beamer` + línea con `m3: "muro"` (o forma `vol`) donde cae la imagen |
| Proyección sobre el piso o la escenografía | `surface` `floor` o `scenery`. En planta: `sp-stage` con `m3: "piso"`, o formas `vol` para escenografía |
| Televisión, monitor o pantalla en escena | Par vinculado (8): `d-crt` o `d-flat` y su tarjeta, conectada desde un conversor o la computadora |
| Estética noventera o análoga | `tv-crt`, `dvcam`, `vhs`, `cctv-ana`, `dvr`, `quad`, `vga2rca`, `hdmi2rca`, cables de video compuesto |
| Cámara en vivo | `webcam`, `videocam`, `ptz`, `dvcam` + capturadora (`capture`, `anacapture`) hacia la computadora |
| Muro LED, pantalla gigante o fondo curvo | `d-led` o `d-ledc` (planta) + `ledwall` y `ledproc` (señal, por LAN) |
| Interacción o control en vivo | `midictl`, `touchosc`, `sensor`, `streamdeck`, `pedal`, `dmxif` |
| Operación y software | `laptop`, `mediaserver` o `pc` + `sw-isadora`, `sw-touchdesigner`, `sw-qlab`… + `op-video` (persona) |
| Personajes o elenco | Figuras `f-*` en planta con su estatura y `label` del personaje |
| Iluminación por escena | Luminarias `l-*` con `pos`, `color`, `prop`, `canal`/`addr` |
| Energía en cabina o escena | `e-toma`, `e-ext`, `e-multi`, `e-ups`, `e-12v` con cables de corriente |
| Montaje | `mount`, `base`, `tripod`, `clamp`, `safety`, o formas de planta `p-truss`, `p-vara`, `p-ped` |

Agrega siempre una **nota** (`note`) arriba del diagrama con el título y 2–4 viñetas del concepto, y llena `project.descripcion`.

## 10. Validación antes de entregar

Lista de verificación:
- [ ] `nodes` y `cables` existen; todos los `id` son únicos.
- [ ] Cada cable: `a.s` es "out" y `b.s` es "in"; `a.i` es menor que `outs.length` del nodo origen y `b.i` es menor que `ins.length` del nodo destino.
- [ ] Cada `key` existe en el apéndice A y `cls` y `t` coinciden con su fila.
- [ ] Las formas, notas, pantallas y figuras llevan su plantilla completa.
- [ ] Los pares vinculados se apuntan mutuamente (`link` ↔ `link3`).
- [ ] Las tarjetas caen dentro de su hoja y la planta dentro de la suya.

Validador mínimo (Node.js):
```js
const d = JSON.parse(require('fs').readFileSync(process.argv[2], 'utf8'));
const doc = d.doc || d, ids = new Map(doc.nodes.map(n => [n.id, n])), err = [];
if (ids.size !== doc.nodes.length) err.push('ids de nodo repetidos');
doc.cables.forEach(c => {
  const A = ids.get(c.a.n), B = ids.get(c.b.n);
  if (!A || !B) return err.push(`cable ${c.id}: nodo inexistente`);
  if (c.a.s !== 'out' || c.b.s !== 'in') err.push(`cable ${c.id}: a debe ser out y b in`);
  if (!(c.a.i < A.outs.length)) err.push(`cable ${c.id}: ${A.id} no tiene la salida ${c.a.i}`);
  if (!(c.b.i < B.ins.length)) err.push(`cable ${c.id}: ${B.id} no tiene la entrada ${c.b.i}`);
});
doc.nodes.forEach(n => { if (n.link && ids.get(n.link)?.link3 !== n.id) err.push(`${n.id}: vínculo sin pareja`); });
console.log(err.length ? err.join('\n') : 'OK');
```

## 11. Ejemplo completo (probado en Ezchema v0.39)

"Ecos del agua": proyección frontal sobre ciclorama, TV CRT en escena alimentada con la laptop por un conversor HDMI a RCA, cámara MiniDV capturada a la laptop, Isadora, multicontacto, dos figuras de escala y un leko frontal. Lleva **dos hojas carta verticales**: señal y planta.

Al abrirlo, Ezchema genera la lista (7 equipos y 5 cables) y la revisión avisa del HDMI de 30 m y de que la imagen de 8.2 m no cubre el ciclorama de 10 m. Esos avisos son útiles en un borrador.

```json
{
 "format": "ezchema",
 "v": 4,
 "project": {
  "nombre": "Ecos del agua",
  "compania": "Landscape Artes Escénicas",
  "disenador": "Héctor Cruz Juárez",
  "venue": "Teatro de la Ciudad",
  "fecha": "2026-11-20",
  "version": "borrador 1",
  "contacto": "",
  "descripcion": "Proyección frontal sobre ciclorama y TV CRT en escena con cámara en vivo. Operación desde cabina con Isadora.",
  "logo": ""
 },
 "theme": "claro",
 "scale": 50,
 "cardScale": 1,
 "paper": {
  "size": "carta",
  "orient": "v"
 },
 "legend": true,
 "layers": [
  {
   "id": "L1",
   "name": "General",
   "vis": true,
   "lock": false,
   "op": 100
  }
 ],
 "activeLayer": "L1",
 "versions": [],
 "pages": [
  {
   "id": "pg1",
   "size": "carta",
   "orient": "v",
   "title": "Hoja 1 · Señal",
   "x": -40,
   "y": -60
  },
  {
   "id": "pg2",
   "size": "carta",
   "orient": "v",
   "title": "Hoja 2 · Planta",
   "x": 900,
   "y": -60
  }
 ],
 "nodes": [
  {
   "id": "n-nota",
   "key": "note",
   "cls": "note",
   "t": "note",
   "x": 20,
   "y": -10,
   "label": "Caja de texto",
   "text": "# Ecos del agua\nDispositivo de borrador generado desde el análisis del texto.\n- Proyección frontal sobre ciclorama\n- TV CRT en escena con la cámara en vivo\n- Prólogo · loop de imágenes del agua, ciclorama",
   "w": 520,
   "fs": 12.5,
   "align": "left",
   "fill": "#ffffff",
   "fillOp": 0,
   "stroke": "#141517",
   "strokeOp": 0,
   "sw": 1,
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "n-cyc",
   "key": "cyc",
   "cls": "surface",
   "t": "cyc",
   "x": 20,
   "y": 120,
   "label": "Ciclorama",
   "mat": "Ciclorama blanco",
   "sw": 10,
   "sh": 5,
   "model": "",
   "qty": 1,
   "provee": "Teatro / venue",
   "notes": "",
   "url": "",
   "ins": [
    "Proyección"
   ],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "n-tvc",
   "key": "tv-crt",
   "cls": "device",
   "t": "crt",
   "x": 380,
   "y": 120,
   "label": "TV en escena",
   "model": "",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "",
   "ins": [
    "Video compuesto (RCA)"
   ],
   "outs": [],
   "layer": "L1",
   "link3": "n-tvp"
  },
  {
   "id": "n-pry",
   "key": "proj-std",
   "cls": "device",
   "t": "projector",
   "x": 20,
   "y": 260,
   "label": "Proyector frontal",
   "model": "",
   "qty": 1,
   "provee": "Renta",
   "notes": "",
   "url": "",
   "lens": "std",
   "trMin": 1.4,
   "trMax": 2.2,
   "lm": 7000,
   "ins": [
    "HDMI"
   ],
   "outs": [
    "Lente"
   ],
   "layer": "L1"
  },
  {
   "id": "n-cam",
   "key": "dvcam",
   "cls": "device",
   "t": "anacam",
   "x": 380,
   "y": 260,
   "label": "Cámara MiniDV",
   "model": "Sony Handycam",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [
    "Video compuesto"
   ],
   "layer": "L1"
  },
  {
   "id": "n-cap",
   "key": "anacapture",
   "cls": "device",
   "t": "capture",
   "x": 380,
   "y": 400,
   "label": "Capturadora análoga",
   "model": "",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "",
   "ins": [
    "Video compuesto"
   ],
   "outs": [
    "USB"
   ],
   "layer": "L1"
  },
  {
   "id": "n-h2r",
   "key": "hdmi2rca",
   "cls": "device",
   "t": "converter",
   "x": 20,
   "y": 400,
   "label": "Conversor HDMI a RCA",
   "model": "",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "",
   "ins": [
    "HDMI"
   ],
   "outs": [
    "Video compuesto"
   ],
   "layer": "L1"
  },
  {
   "id": "n-lap",
   "key": "laptop",
   "cls": "device",
   "t": "laptop",
   "x": 200,
   "y": 540,
   "label": "Operación de video",
   "model": "MacBook Pro",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "",
   "ins": [
    "USB 1"
   ],
   "outs": [
    "HDMI",
    "HDMI 2"
   ],
   "layer": "L1"
  },
  {
   "id": "n-isa",
   "key": "sw-isadora",
   "cls": "software",
   "t": "software",
   "x": 470,
   "y": 540,
   "label": "Isadora",
   "model": "Isadora 3",
   "qty": 1,
   "provee": "Producción",
   "notes": "",
   "url": "https://troikatronix.com",
   "ins": [],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "n-ext",
   "key": "e-multi",
   "cls": "device",
   "t": "strip",
   "x": 20,
   "y": 680,
   "label": "Multicontacto cabina",
   "model": "",
   "qty": 1,
   "provee": "Teatro / venue",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [
    "Contacto 1"
   ],
   "layer": "L1"
  },
  {
   "id": "n-op",
   "key": "op-video",
   "cls": "person",
   "t": "person",
   "x": 300,
   "y": 700,
   "label": "Operador de video",
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "s-esc",
   "key": "sp-stage",
   "cls": "shape",
   "t": "shrect",
   "x": 1050,
   "y": 40,
   "label": "Escenario",
   "shape": "rect",
   "w": 500,
   "h": 400,
   "sides": 6,
   "fill": "#141517",
   "fillOp": 7,
   "stroke": "#141517",
   "strokeOp": 70,
   "sw": 1.5,
   "dash": false,
   "head": "none",
   "dim": false,
   "text": "Escenario",
   "fs": 13,
   "tc": "#60666E",
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1",
   "noList": true
  },
  {
   "id": "s-cyc",
   "key": "sh-line",
   "cls": "shape",
   "t": "shline",
   "x": 1060,
   "y": 50,
   "label": "Ciclorama (planta)",
   "shape": "line",
   "w": 480,
   "h": 0,
   "sides": 6,
   "fill": "#141517",
   "fillOp": 0,
   "stroke": "#141517",
   "strokeOp": 85,
   "sw": 2,
   "dash": false,
   "head": "none",
   "dim": false,
   "text": "Ciclorama",
   "fs": 13,
   "tc": "#141517",
   "m3": "muro",
   "z3": 0,
   "h3": 5,
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1",
   "noList": true
  },
  {
   "id": "n-bpl",
   "key": "beamer",
   "cls": "beamer",
   "t": "beamer",
   "x": 1300,
   "y": 640,
   "label": "Proyector frontal",
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "noList": true,
   "lens": "std",
   "trMin": 1.4,
   "trMax": 2.2,
   "zoom": 50,
   "lm": 7000,
   "dist": 11.5,
   "ang": 0,
   "bw": 0.5,
   "bd": 0.45,
   "aspect": "16:9",
   "h3": 2.2,
   "tilt": 0,
   "lsv": 20,
   "lsh": 0,
   "layer": "L1"
  },
  {
   "id": "n-tvp",
   "key": "d-crt",
   "cls": "disp",
   "t": "tv",
   "x": 1130,
   "y": 330,
   "label": "TV en escena",
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "dt": "crt",
   "z3": 0.75,
   "nits": 150,
   "watts": 90,
   "diag": 21,
   "aspect": "4:3",
   "res": "640×480",
   "fx": true,
   "ang": 0,
   "src3": "patron",
   "link": "n-tvc",
   "noList": true,
   "layer": "L1"
  },
  {
   "id": "f-1",
   "key": "f-mujer2",
   "cls": "fig",
   "t": "fig",
   "x": 1250,
   "y": 260,
   "label": "Intérprete",
   "fk": "mujer",
   "alt": 1.65,
   "ang": 0,
   "noList": true,
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "f-2",
   "key": "f-hombre",
   "cls": "fig",
   "t": "fig",
   "x": 1360,
   "y": 280,
   "label": "",
   "fk": "hombre",
   "alt": 1.7,
   "ang": 0,
   "noList": true,
   "model": "",
   "qty": 1,
   "provee": "",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "layer": "L1"
  },
  {
   "id": "l-1",
   "key": "l-leko26",
   "cls": "lum",
   "t": "lleko",
   "x": 1200,
   "y": 480,
   "label": "Leko 26°",
   "model": "",
   "qty": 1,
   "provee": "Teatro / venue",
   "notes": "",
   "url": "",
   "ins": [],
   "outs": [],
   "lt": "leko",
   "angMin": 26,
   "angMax": 26,
   "zoom": 50,
   "watts": 575,
   "led": false,
   "modo": 1,
   "canal": 1,
   "univ": 1,
   "addr": 1,
   "color": "L201",
   "gobo": "",
   "pos": "Vara 1",
   "prop": "Frontal intérprete",
   "alt": 6,
   "foco": 1.5,
   "dist": 4,
   "ang": 0,
   "beam": true,
   "plens": "",
   "layer": "L1"
  }
 ],
 "cables": [
  {
   "id": "c1",
   "a": {
    "n": "n-lap",
    "s": "out",
    "i": 0
   },
   "b": {
    "n": "n-pry",
    "s": "in",
    "i": 0
   },
   "label": "",
   "len": 30,
   "provee": "Producción"
  },
  {
   "id": "c2",
   "a": {
    "n": "n-pry",
    "s": "out",
    "i": 0
   },
   "b": {
    "n": "n-cyc",
    "s": "in",
    "i": 0
   },
   "label": "",
   "len": "",
   "provee": "",
   "dist": 11.5
  },
  {
   "id": "c3",
   "a": {
    "n": "n-cam",
    "s": "out",
    "i": 0
   },
   "b": {
    "n": "n-cap",
    "s": "in",
    "i": 0
   },
   "label": "RCA",
   "len": 15,
   "provee": "Producción"
  },
  {
   "id": "c4",
   "a": {
    "n": "n-cap",
    "s": "out",
    "i": 0
   },
   "b": {
    "n": "n-lap",
    "s": "in",
    "i": 0
   },
   "label": "",
   "len": 2,
   "provee": "Producción"
  },
  {
   "id": "c5",
   "a": {
    "n": "n-lap",
    "s": "out",
    "i": 1
   },
   "b": {
    "n": "n-h2r",
    "s": "in",
    "i": 0
   },
   "label": "",
   "len": 2,
   "provee": "Producción"
  },
  {
   "id": "c6",
   "a": {
    "n": "n-h2r",
    "s": "out",
    "i": 0
   },
   "b": {
    "n": "n-tvc",
    "s": "in",
    "i": 0
   },
   "label": "RCA",
   "len": 20,
   "provee": "Producción"
  }
 ]
}
```

## Apéndice A · Catálogo de claves (Ezchema v0.39)

### Fuentes

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `laptop` | device | laptop | Laptop | USB 1, USB 2 | HDMI, USB-C DP, LAN |
| `mediaserver` | device | server | Media server | Captura HDMI, Captura SDI, USB | DP 1, DP 2, DP 3, DP 4, LAN |
| `pc` | device | tower | Computadora | USB | HDMI, DP, LAN |
| `ipad` | device | tablet | iPad o tableta | — | USB-C, AirPlay, Wi-Fi, NDI |
| `iphone` | device | phone | iPhone | — | USB-C, Lightning, AirPlay, Wi-Fi, NDI |
| `android` | device | phone | Teléfono Android | — | USB-C, Wi-Fi, NDI |
| `player` | device | player | Reproductor de video | USB | HDMI, Video compuesto |
| `wireless` | device | wireless | Receptor inalámbrico (AirPlay, Chromecast) | Wi-Fi | HDMI |
| `vhs` | device | vhs | Reproductor VHS o DVD | — | Video compuesto, S-Video, Audio RCA L/R |
| `gen-src` | device | tower | Fuente genérica | Entrada | Salida 1, Salida 2 |

### Cámaras

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `ptz` | device | ptz | Cámara PTZ | LAN control | SDI, HDMI, LAN NDI |
| `videocam` | device | camera | Cámara de video | — | SDI, HDMI |
| `cctv` | device | cctv | Cámara CCTV | — | LAN PoE |
| `webcam` | device | webcam | Webcam | — | USB |
| `anacam` | device | anacam | Cámara análoga | — | Video compuesto, S-Video |
| `dvcam` | device | anacam | Cámara DV / MiniDV (Handycam) | — | Video compuesto, S-Video, FireWire DV, Audio RCA L/R |
| `cctv-ana` | device | cctv | Cámara de circuito cerrado análoga | Corriente 12 V | Video compuesto |
| `phonecam` | device | phone | Celular como cámara | — | NDI, USB-C, Wi-Fi |
| `gen-cam` | device | camera | Cámara genérica | Control | Salida de video |

### Control e interacción

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `midictl` | device | midictl | Controlador MIDI | — | USB MIDI, MIDI DIN |
| `midiif` | device | adapter | Interfaz MIDI | MIDI DIN | USB MIDI |
| `touchosc` | device | tablet | TouchOSC en iPad o tableta | — | OSC Wi-Fi, MIDI |
| `oscapp` | device | phone | App de control OSC (celular) | — | OSC Wi-Fi |
| `streamdeck` | device | streamdeck | Stream Deck o botonera | — | USB |
| `kbmouse` | device | keyboard | Teclado y mouse | — | USB |
| `pedal` | device | pedal | Pedal o footswitch | — | USB MIDI |
| `sensor` | device | sensor | Sensor o Arduino | — | USB, OSC |
| `dmxif` | device | dmx | Interfaz DMX | USB, Art-Net | DMX |
| `gen-ctl` | device | midictl | Control genérico | — | Control |

### Procesamiento

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `switcher` | device | switcher | Switcher de video | HDMI 1, HDMI 2, HDMI 3, HDMI 4 | PGM HDMI, Aux HDMI, USB webcam, LAN |
| `matrox` | device | split3 | Matrox TripleHead2Go | DP | HDMI 1, HDMI 2, HDMI 3 |
| `datapath` | device | split4 | Datapath Fx4 | DP, HDMI | HDMI 1, HDMI 2, HDMI 3, HDMI 4 |
| `splitter` | device | split4 | Splitter HDMI 1×4 | HDMI | HDMI 1, HDMI 2, HDMI 3, HDMI 4 |
| `capture` | device | capture | Capturadora USB | HDMI | USB |
| `anacapture` | device | capture | Capturadora de video análogo | Video compuesto, S-Video | USB |
| `ana2hdmi` | device | converter | Conversor análogo a HDMI | Video compuesto, S-Video | HDMI |
| `vga2rca` | device | converter | Conversor VGA a RCA (video compuesto) | VGA | Video compuesto, S-Video |
| `hdmi2rca` | device | converter | Conversor HDMI a RCA (video compuesto) | HDMI | Video compuesto, Audio RCA L/R |
| `dvr` | device | dvr | DVR de circuito cerrado | Video compuesto 1, Video compuesto 2, Video compuesto 3, Video compuesto 4 | VGA, HDMI, LAN |
| `quad` | device | split4 | Divisor cuádruple de video análogo | Video compuesto 1, Video compuesto 2, Video compuesto 3, Video compuesto 4 | Video compuesto |
| `sdi2hdmi` | device | converter | Conversor SDI a HDMI | SDI | HDMI, SDI loop |
| `hdmi2sdi` | device | converter | Conversor HDMI a SDI | HDMI | SDI |
| `hdbttx` | device | hdbt | Extensor HDBaseT Tx | HDMI | HDBT |
| `hdbtrx` | device | hdbt | Extensor HDBaseT Rx | HDBT | HDMI |
| `adapter` | device | adapter | Adaptador | Entrada | Salida |
| `gen-proc` | device | converter | Procesador genérico | Entrada 1, Entrada 2 | Salida 1, Salida 2 |

### Red

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `router` | device | router | Router Wi-Fi | WAN | LAN 1, LAN 2, LAN 3, LAN 4, Wi-Fi |
| `netswitch` | device | netswitch | Switch de red | Uplink | LAN 1, LAN 2, LAN 3, LAN 4, LAN 5, LAN 6 |
| `gen-net` | device | netswitch | Equipo de red genérico | LAN entrada | LAN 1, LAN 2 |

### Proyectores y pantallas

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `proj-std` | device | projector | Proyector | HDMI, HDBT, LAN | Lente |
| `proj-short` | device | projector | Proyector tiro corto | HDMI, HDBT, LAN | Lente |
| `proj-ust` | device | projector | Proyector ultra corto | HDMI, LAN | Lente |
| `proj-long` | device | projector | Proyector tiro largo | HDMI, SDI, HDBT, LAN | Lente |
| `beamer` | beamer | beamer | Proyector en planta (cálculo) | — | — |
| `d-crt` | disp | tv | TV de tubo CRT en planta | — | — |
| `d-flat` | disp | tv | Pantalla plana en planta | — | — |
| `d-led` | disp | led | Muro LED en planta | — | — |
| `d-ledc` | disp | led | Muro LED curvo en planta | — | — |
| `d-wedge` | disp | monitor | Monitor de piso en planta | — | — |
| `d-tablet` | disp | monitor | Tablet o teléfono en planta | — | — |
| `monitor` | device | monitor | Monitor | HDMI, DP | — |
| `tv` | device | tv | Pantalla / TV | HDMI 1, HDMI 2 | — |
| `tv-crt` | device | crt | TV de tubo CRT | Video compuesto (RCA), S-Video, Antena RF | — |
| `mon-cctv` | device | crt | Monitor de circuito cerrado | Video compuesto, VGA | Video compuesto loop |
| `ledproc` | device | ledproc | Procesador LED | HDMI, DVI | LAN datos 1, LAN datos 2 |
| `ledwall` | device | led | Pantalla LED | LAN datos | LAN loop |
| `gen-proj` | device | projector | Proyector genérico | Entrada de video, LAN | Lente |
| `gen-disp` | device | monitor | Pantalla genérica | Entrada de video | — |

### Superficies y materiales

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `gasa` | surface | gauze | Gasa frontal | Proyección | — |
| `rear` | surface | rear | Pantalla de rear | Proyección | — |
| `front` | surface | rear | Pantalla frontal | Proyección | — |
| `cyc` | surface | cyc | Ciclorama | Proyección | — |
| `floor` | surface | floor | Piso | Proyección | — |
| `scenery` | surface | scenery | Escenografía | Proyección | — |
| `gen-surf` | surface | rear | Superficie genérica | Proyección | — |

### Audio

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `mixer` | device | mixer | Consola de audio | Mic 1, Mic 2, Línea L/R, USB | Main L, Main R |
| `speaker` | device | speaker | Bocina activa | XLR | — |
| `gen-audio` | device | mixer | Equipo de audio genérico | Entrada audio | Salida audio |

### Energía

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `e-toma` | device | plug | Toma de corriente (contacto de pared) | — | Contacto 1, Contacto 2 |
| `e-ext` | device | plug | Extensión eléctrica | Clavija | Contacto 1, Contacto 2, Contacto 3 |
| `e-multi` | device | strip | Multicontacto (regleta) | Clavija | Contacto 1, Contacto 2, Contacto 3, Contacto 4, Contacto 5, Contacto 6 |
| `e-ups` | device | ups | Regulador o no-break (UPS) | Clavija | Contacto 1, Contacto 2, Contacto 3, Contacto 4 |
| `e-12v` | device | plug | Eliminador o fuente de 12 V | Clavija | 12 V DC |
| `gen-pwr` | device | strip | Elemento eléctrico genérico | Clavija | Contacto |

### Software y licencias

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `sw-isadora` | software | software | Isadora | — | — |
| `sw-touchdesigner` | software | software | TouchDesigner | — | — |
| `sw-resolumearena` | software | software | Resolume Arena | — | — |
| `sw-qlab` | software | software | QLab | — | — |
| `sw-madmapper` | software | software | MadMapper | — | — |
| `sw-pixera` | software | software | PIXERA | — | — |
| `sw-obsstudio` | software | software | OBS Studio | — | — |
| `gen-sw` | software | software | Software genérico | — | — |

### Montaje y rigging

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `mount` | rigging | mount | Soporte a parrilla con clamp | — | — |
| `base` | rigging | base | Base de piso para proyector | — | — |
| `tripod` | rigging | tripod | Tripié | — | — |
| `clamp` | rigging | clamp | Clamp | — | — |
| `safety` | rigging | safety | Cable de seguridad | — | — |
| `r-bar` | rigging | lpipe | Barra o tubo | — | — |
| `r-truss` | rigging | rtruss | Truss | — | — |
| `r-grid` | rigging | rgrid | Parrilla (estructura) | — | — |
| `r-boom` | rigging | rboom | Árbol o boom | — | — |
| `r-ped` | rigging | rped | Pedestal | — | — |
| `r-table` | rigging | rtable | Mesa | — | — |
| `r-custom` | rigging | rcustom | Montaje personalizado | — | — |
| `gen-rig` | rigging | clamp | Elemento de montaje genérico | — | — |

### Personas

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `op-video` | person | person | Operador de video | — | — |
| `tec-proy` | person | person | Técnico de proyección | — | — |
| `asist` | person | person | Asistente de montaje | — | — |
| `gen-person` | person | person | Persona o rol genérico | — | — |

### Iluminación

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `l-leko19` | lum | lleko | Leko 19° | — | — |
| `l-leko26` | lum | lleko | Leko 26° | — | — |
| `l-leko36` | lum | lleko | Leko 36° | — | — |
| `l-leko50` | lum | lleko | Leko 50° | — | — |
| `l-leko10` | lum | lleko | Leko 10° | — | — |
| `l-lekoz1530` | lum | lleko | Leko zoom 15°–30° | — | — |
| `l-lekoz2550` | lum | lleko | Leko zoom 25°–50° | — | — |
| `l-lekojr` | lum | lleko | Leko Jr. zoom 25°–50° | — | — |
| `l-leko69` | lum | lleko | Leko 6 × 9 (Altman) | — | — |
| `l-lekoled` | lum | lled | Leko LED zoom | — | — |
| `l-par64` | lum | lpar | PAR 64 (1000 W) | — | — |
| `l-s4par` | lum | lpar | Source Four PAR | — | — |
| `l-par56` | lum | lpar | PAR 56 | — | — |
| `l-parled` | lum | lled | PAR LED RGB | — | — |
| `l-fres6` | lum | lfres | Fresnel 6" | — | — |
| `l-fres8` | lum | lfres | Fresnel 8" | — | — |
| `l-fresled` | lum | lled | Fresnel LED | — | — |
| `l-cyc` | lum | lcyc | Ciclorama (3 o 4 celdas) | — | — |
| `l-cycled` | lum | lcyc | Ciclorama LED | — | — |
| `l-diabla` | lum | lcyc | Diabla o luz de piso | — | — |
| `l-seg` | lum | lseg | Seguidor | — | — |
| `l-wash` | lum | lmov | Cabeza móvil wash | — | — |
| `l-spot` | lum | lmov | Cabeza móvil spot | — | — |
| `l-vl2500w` | lum | lmov | Vari-Lite VL2500 Wash | — | — |
| `l-vl2500s` | lum | lmov | Vari-Lite VL2500 Spot | — | — |
| `l-gen` | lum | lleko | Luminaria genérica | — | — |

### Montaje en planta

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `p-vara` | shape | lpipe | Vara eléctrica | — | — |
| `p-puente` | shape | lpipe | Puente de sala | — | — |
| `p-arbol` | shape | lpipe | Árbol o varal de calle | — | — |
| `p-caja` | shape | lpipe | Caja de piso | — | — |
| `p-barra` | shape | lpipe | Barra o tubo (planta) | — | — |
| `p-truss` | shape | rtruss | Truss (planta) | — | — |
| `p-grid` | shape | rgrid | Parrilla (planta) | — | — |
| `p-ped` | shape | rped | Pedestal (planta) | — | — |
| `p-tri` | shape | tripod | Tripié (planta) | — | — |
| `p-mesa` | shape | rtable | Mesa (planta) | — | — |
| `p-custom` | shape | rcustom | Montaje personalizado (planta) | — | — |

### Figuras de escala

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `f-mujer` | fig | fig | Mujer (1.60 m) | — | — |
| `f-mujer2` | fig | fig | Mujer (1.65 m) | — | — |
| `f-hombre` | fig | fig | Hombre (1.70 m) | — | — |
| `f-hombre2` | fig | fig | Hombre (1.75 m) | — | — |
| `f-nina` | fig | fig | Niña (1.15 m) | — | — |
| `f-nino` | fig | fig | Niño (1.20 m) | — | — |
| `f-grupo` | fig | fig | Grupo de personas (mujer, hombre, niño) | — | — |

### Espacio escénico

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `sp-stage` | shape | shrect | Escenario | — | — |
| `sp-seats` | shape | shrect | Área de butacas | — | — |
| `sp-booth` | shape | shrect | Cabina | — | — |
| `sp-prosc` | shape | shdim | Boca de escena (cota) | — | — |
| `sp-wing` | shape | shrect | Pierna o pata | — | — |
| `sp-perf` | shape | shellipse | Intérprete (figura) | — | — |
| `sp-table` | shape | shrect | Mesa de operación | — | — |

### Boceto

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `note` | note | note | Caja de texto | — | — |
| `note-call` | note | notecall | Nota con flecha (comentario) | — | — |
| `note-arrow` | note | notearrow | Flecha con texto | — | — |
| `sh-pencil` | shape | pencil | Lápiz (trazo libre) | — | — |
| `sh-rect` | shape | shrect | Rectángulo | — | — |
| `sh-ellipse` | shape | shellipse | Círculo o elipse | — | — |
| `sh-tri` | shape | shtri | Triángulo | — | — |
| `sh-poly` | shape | shpoly | Polígono | — | — |
| `sh-free` | shape | shfree | Forma libre (por puntos) | — | — |
| `sh-arrow` | shape | sharrow | Flecha | — | — |
| `sh-line` | shape | shline | Línea | — | — |
| `sh-dim` | shape | shdim | Cota (medida) | — | — |
| `image` | image | image | Imagen o referencia | — | — |

### Otros

| key | cls | t | Nombre | Entradas sugeridas | Salidas sugeridas |
|---|---|---|---|---|---|
| `generic` | device | generic | Equipo genérico | Entrada 1 | Salida 1 |

## Apéndice B · Plantillas completas por clase
Copia estas plantillas (salidas de Ezchema al crear cada elemento) y agrega `id`, `x`, `y` y `layer`.

**`laptop`**
```json
{"key": "laptop", "cls": "device", "t": "laptop", "x": 0, "y": 0, "label": "Laptop", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": []}
```
**`proj-std`**
```json
{"key": "proj-std", "cls": "device", "t": "projector", "x": 0, "y": 0, "label": "Proyector", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "lens": "std", "trMin": 1.4, "trMax": 2.2, "lm": ""}
```
**`cyc`**
```json
{"key": "cyc", "cls": "surface", "t": "cyc", "x": 0, "y": 0, "label": "Ciclorama", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "mat": "Ciclorama", "sw": 8, "sh": 5}
```
**`sw-isadora`**
```json
{"key": "sw-isadora", "cls": "software", "t": "software", "x": 0, "y": 0, "label": "Isadora", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": []}
```
**`mount`**
```json
{"key": "mount", "cls": "rigging", "t": "mount", "x": 0, "y": 0, "label": "Soporte a parrilla con clamp", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": []}
```
**`op-video`**
```json
{"key": "op-video", "cls": "person", "t": "person", "x": 0, "y": 0, "label": "Operador de video", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": []}
```
**`note`**
```json
{"key": "note", "cls": "note", "t": "note", "x": 0, "y": 0, "label": "Caja de texto", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "text": "# Título\nEscribe aquí una idea, una descripción o una indicación de montaje.", "w": 280, "fs": 12.5, "align": "left", "fill": "#ffffff", "fillOp": 0, "stroke": "#141517", "strokeOp": 0, "sw": 1}
```
**`note-call`**
```json
{"key": "note-call", "cls": "note", "t": "notecall", "x": 0, "y": 0, "label": "Nota con flecha (comentario)", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "text": "# Nota\nEscribe aquí el comentario.", "w": 220, "fs": 12.5, "align": "left", "fill": "#fff3c4", "fillOp": 100, "stroke": "#c9a227", "strokeOp": 70, "sw": 1, "ptr": {"x": -90, "y": 150}, "ac": "#141517", "aw": 1.8}
```
**`sp-stage`**
```json
{"key": "sp-stage", "cls": "shape", "t": "shrect", "x": 0, "y": 0, "label": "Escenario", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "shape": "rect", "w": 500, "h": 400, "sides": 6, "fill": "#141517", "fillOp": 7, "stroke": "#141517", "strokeOp": 70, "sw": 1.5, "dash": false, "head": "none", "dim": false, "text": "Escenario", "fs": 13, "tc": "#60666E"}
```
**`sh-line`**
```json
{"key": "sh-line", "cls": "shape", "t": "shline", "x": 0, "y": 0, "label": "Línea", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "shape": "line", "w": 200, "h": -80, "sides": 6, "fill": "#141517", "fillOp": 0, "stroke": "#141517", "strokeOp": 80, "sw": 2, "dash": false, "head": "none", "dim": false, "text": "", "fs": 13, "tc": "#141517"}
```
**`beamer`**
```json
{"key": "beamer", "cls": "beamer", "t": "beamer", "x": 0, "y": 0, "label": "Proyector", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "noList": true, "lens": "std", "trMin": 1.4, "trMax": 2.2, "zoom": 50, "lm": 5000, "dist": 6, "ang": 0, "bw": 0.5, "bd": 0.45, "aspect": "16:9", "h3": 1.5, "tilt": 0, "lsv": 0, "lsh": 0}
```
**`l-leko26`**
```json
{"key": "l-leko26", "cls": "lum", "t": "lleko", "x": 0, "y": 0, "label": "Leko 26°", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "lt": "leko", "angMin": 26, "angMax": 26, "zoom": 50, "watts": 575, "led": false, "modo": 1, "canal": 1, "univ": 1, "addr": 1, "color": "", "gobo": "", "pos": "", "prop": "", "alt": 6, "foco": 1.5, "dist": 3, "ang": 0, "beam": true, "plens": ""}
```
**`d-crt`**
```json
{"key": "d-crt", "cls": "disp", "t": "tv", "x": 0, "y": 0, "label": "TV de tubo CRT", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "dt": "crt", "z3": 0.75, "nits": 150, "watts": 90, "diag": 21, "aspect": "4:3", "res": "640×480", "fx": true, "ang": 0, "src3": "patron"}
```
**`d-led`**
```json
{"key": "d-led", "cls": "disp", "t": "led", "x": 0, "y": 0, "label": "Muro LED", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "dt": "led", "z3": 0, "nits": 1500, "watts": "", "cols": 8, "rows": 5, "modW": 0.5, "modH": 0.5, "pitch": 3.9, "R": 0, "ang": 0, "src3": "patron"}
```
**`d-ledc`**
```json
{"key": "d-ledc", "cls": "disp", "t": "led", "x": 0, "y": 0, "label": "Muro LED curvo", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "dt": "ledc", "z3": 0, "nits": 1500, "watts": "", "cols": 12, "rows": 5, "modW": 0.5, "modH": 0.5, "pitch": 3.9, "R": 6, "ang": 0, "src3": "patron"}
```
**`f-mujer2`**
```json
{"key": "f-mujer2", "cls": "fig", "t": "fig", "x": 0, "y": 0, "label": "", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "fk": "mujer", "alt": 1.65, "ang": 0, "noList": true}
```
**`f-grupo`**
```json
{"key": "f-grupo", "cls": "fig", "t": "fig", "x": 0, "y": 0, "label": "", "model": "", "qty": 1, "provee": "", "notes": "", "url": "", "ins": [], "outs": [], "fk": "grupo", "alt": 1.65, "ang": 0, "noList": true}
```

## Apéndice C · Valores de referencia

**Ópticas de proyector (`lens`):**

| id | Nombre | Throw ratio |
|---|---|---|
| `std` | Óptica estándar | 1.4 – 2.2 |
| `short` | Tiro corto | 0.5 – 0.8 |
| `ust` | Ultra tiro corto | 0.25 – 0.35 |
| `long` | Tiro largo | 4 – 7 |
| `zoom` | Lente intercambiable | — – — |

**Luminarias del catálogo:**

| key | Nombre | lt | Ángulo | Potencia |
|---|---|---|---|---|
| `l-leko19` | Leko 19° | leko | 19–19° | 575 W |
| `l-leko26` | Leko 26° | leko | 26–26° | 575 W |
| `l-leko36` | Leko 36° | leko | 36–36° | 575 W |
| `l-leko50` | Leko 50° | leko | 50–50° | 575 W |
| `l-leko10` | Leko 10° | leko | 10–10° | 750 W |
| `l-lekoz1530` | Leko zoom 15°–30° | leko | 15–30° | 575 W |
| `l-lekoz2550` | Leko zoom 25°–50° | leko | 25–50° | 575 W |
| `l-lekojr` | Leko Jr. zoom 25°–50° | leko | 25–50° | 575 W |
| `l-leko69` | Leko 6 × 9 (Altman) | leko | 37–37° | 1000 W |
| `l-lekoled` | Leko LED zoom | leko | 15–50° | 200 W LED |
| `l-par64` | PAR 64 (1000 W) | par | 28–28° | 1000 W |
| `l-s4par` | Source Four PAR | par | 28–28° | 575 W |
| `l-par56` | PAR 56 | par | 28–28° | 300 W |
| `l-parled` | PAR LED RGB | par | 25–25° | 120 W LED |
| `l-fres6` | Fresnel 6" | fres | 15–55° | 575 W |
| `l-fres8` | Fresnel 8" | fres | 15–60° | 1000 W |
| `l-fresled` | Fresnel LED | fres | 15–50° | 200 W LED |
| `l-cyc` | Ciclorama (3 o 4 celdas) | cyc | 100–100° | 3000 W |
| `l-cycled` | Ciclorama LED | cyc | 100–100° | 240 W LED |
| `l-diabla` | Diabla o luz de piso | cyc | 100–100° | 1500 W |
| `l-seg` | Seguidor | seg | 5–15° | 1200 W |
| `l-wash` | Cabeza móvil wash | mov | 14–55° | 575 W LED |
| `l-spot` | Cabeza móvil spot | mov | 18–42° | 700 W LED |
| `l-vl2500w` | Vari-Lite VL2500 Wash | mov | 14–55.5° | 700 W LED |
| `l-vl2500s` | Vari-Lite VL2500 Spot | mov | 18.5–42° | 700 W LED |
| `l-gen` | Luminaria genérica | leko | 30–30° | 575 W |

**Pantallas en planta (`dt`) y sus valores iniciales:**

| dt | Tipo | Tamaño | Base (z3) | nits |
|---|---|---|---|---|
| `crt` | TV de tubo CRT | 21" 4:3 · 640×480 | 0.75 m | 150 |
| `flat` | Pantalla plana | 55" 16:9 · 3840×2160 | 0.9 m | 400 |
| `wedge` | Monitor de piso | 32" 16:9 · 1920×1080 | 0 m | 350 |
| `tablet` | Tablet o teléfono | 11" 4:3 · 2388×1668 | 1 m | 500 |
| `led` | Muro LED | 8×5 módulos de 0.5×0.5 m · pitch 3.9 mm · R 0 m | 0 m | 1500 |
| `ledc` | Muro LED curvo | 12×5 módulos de 0.5×0.5 m · pitch 3.9 mm · R 6 m | 0 m | 1500 |

**Tipos de cable (`kind`):**

| kind | Nombre |
|---|---|
| `hdmi` | HDMI |
| `sdi` | SDI / BNC |
| `dp` | DisplayPort |
| `hdbt` | HDBaseT |
| `lan` | Red / Ethernet |
| `wifi` | Wi-Fi |
| `usb` | USB |
| `audio` | Audio |
| `dvi` | DVI / VGA |
| `ana` | Video análogo (RCA, BNC) |
| `pwr` | Corriente eléctrica |
| `ctl` | Control (MIDI, DMX) |
| `beam` | Haz de proyección |
| `gen` | Genérico |

**Otros valores:**

| Campo | Valores |
|---|---|
| Figuras (`fk`) | mujer, hombre, nina, nino, grupo |
| `provee` | "", "Producción", "Teatro / venue", "Renta", "Por definir" |
| Papel (`size`) | carta, a4, tabloide, a3 |
| Orientación (`orient`) | v (vertical), h (horizontal) |
| Temas (`theme`) | claro, blanco, oscuro |

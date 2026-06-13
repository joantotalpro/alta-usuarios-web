<?php
// --- CORS (permite que el frontend en GitHub Pages llame a este backend) ---
// En producción, sustituye '*' por tu URL exacta de GitHub Pages, p.ej.:
//   header('Access-Control-Allow-Origin: https://joantotalpro.github.io');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// Solo aceptamos POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

// Recogida y saneado básico de datos
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$fecha  = isset($_POST['fecha_alta']) ? trim($_POST['fecha_alta']) : '';

// Validación: nombre
$len = function_exists('mb_strlen') ? mb_strlen($nombre) : strlen($nombre);
if ($nombre === '' || $len < 3 || $len > 50) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El nombre de usuario debe tener entre 3 y 50 caracteres.'
    ]);
    exit;
}

if (!preg_match('/^[\p{L}\p{N} _.-]+$/u', $nombre)) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El nombre de usuario contiene caracteres no permitidos.'
    ]);
    exit;
}

// Validación: fecha (formato YYYY-MM-DD)
$d = DateTime::createFromFormat('Y-m-d', $fecha);
if (!$d || $d->format('Y-m-d') !== $fecha) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'La fecha de alta no es válida (formato esperado AAAA-MM-DD).'
    ]);
    exit;
}

// Persistencia en fichero JSON Lines
$fichero = __DIR__ . DIRECTORY_SEPARATOR . 'usuarios.json';

$registro = [
    'id'         => uniqid('u_', true),
    'nombre'     => $nombre,
    'fecha_alta' => $fecha,
    'creado_en'  => date('c'),
];

$linea = json_encode($registro, JSON_UNESCAPED_UNICODE) . PHP_EOL;

$fp = @fopen($fichero, 'ab');
if ($fp === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensaje' => 'No se pudo abrir el fichero de usuarios.']);
    exit;
}

if (!flock($fp, LOCK_EX)) {
    fclose($fp);
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensaje' => 'No se pudo bloquear el fichero.']);
    exit;
}

$escrito = fwrite($fp, $linea);
fflush($fp);
flock($fp, LOCK_UN);
fclose($fp);

if ($escrito === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensaje' => 'Error al guardar el usuario.']);
    exit;
}

echo json_encode([
    'ok'       => true,
    'mensaje'  => "Usuario '{$nombre}' dado de alta correctamente.",
    'registro' => $registro,
]);

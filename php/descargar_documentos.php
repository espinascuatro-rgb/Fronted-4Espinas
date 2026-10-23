<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $conexion->prepare("SELECT nombre_original, nombre_guardado FROM documento WHERE id_documento = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();

if (!$doc) {
    http_response_code(404);
    die('Documento no encontrado.');
}

$ruta = __DIR__ . '/../admins/' . $doc['nombre_guardado'];

if (!is_file($ruta)) {
    http_response_code(404);
    die('El archivo no existe en el servidor.');
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . rawurlencode($doc['nombre_original']) . '"');
header('Content-Length: ' . filesize($ruta));
readfile($ruta);
exit;
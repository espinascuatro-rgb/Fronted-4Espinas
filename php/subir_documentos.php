<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo'])) {
    header('Location: ../pages/admdocu.php');
    exit;
}

$archivo = $_FILES['archivo'];

if ($archivo['error'] !== UPLOAD_ERR_OK) {
    die('Error al subir el archivo (código ' . $archivo['error'] . ').');
}

$permitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $permitidas, true)) {
    die('Tipo de archivo no permitido.');
}

if ($archivo['size'] > 10 * 1024 * 1024) {
    die('El archivo supera los 10 MB.');
}

$carpeta = __DIR__ . '/../uploads/';
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0755, true);
}

$nombre_guardado = bin2hex(random_bytes(16)) . '.' . $extension;

if (!move_uploaded_file($archivo['tmp_name'], $carpeta . $nombre_guardado)) {
    die('No se pudo guardar el archivo.');
}

$nombre_original = basename($archivo['name']);

$stmt = $conexion->prepare("INSERT INTO documento (nombre_original, nombre_guardado, tipo) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre_original, $nombre_guardado, $extension);
$stmt->execute();

header('Location: ../pages/admdocu.php');
exit;
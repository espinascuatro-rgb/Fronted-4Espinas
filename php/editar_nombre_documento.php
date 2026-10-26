<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id_documento'] ?? 0);
    $nuevoNombre = trim($_POST['nuevo_nombre'] ?? '');

    if ($id > 0 && !empty($nuevoNombre)) {
        $stmt = $con->prepare("UPDATE documentos SET nombre_personalizado = ? WHERE id_documento = ?");
        $stmt->bind_param("si", $nuevoNombre, $id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ../pages/administracion-de-documentos.php');
exit;
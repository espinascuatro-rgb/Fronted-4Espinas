<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id_organo'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 0);

    if ($id > 0 && $cantidad >= 0) {
        $stmt = $con->prepare("UPDATE organo SET cantidad = ? WHERE id_organo = ?");
        $stmt->bind_param("ii", $cantidad, $id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ../pages/gestion-organos.php');
exit;
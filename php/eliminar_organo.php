<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id > 0) {
        $stmt = $con->prepare("DELETE FROM organo WHERE id_organo = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ../pages/gestion-organos.php');
exit;
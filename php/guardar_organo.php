<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_organo'] ?? '');
    $cantidad = (int)($_POST['cantidad'] ?? 0);
    $tiempo = (int)($_POST['tiempo_isquemia_horas'] ?? 4);

    if (!empty($nombre) && $cantidad >= 0) {
        $stmt = $con->prepare("INSERT INTO organo (nombre_organo, cantidad, tiempo_isquemia_horas) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)");
        $stmt->bind_param("sii", $nombre, $cantidad, $tiempo);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: ../pages/gestion-organos.php');
exit;
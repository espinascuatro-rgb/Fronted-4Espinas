<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Buscar la ruta física para borrar el archivo del servidor
    $stmt = $con->prepare("SELECT ruta_archivo FROM documentos WHERE id_documento = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($row = $res->fetch_assoc()) {
        $rutaFisica = __DIR__ . '/../' . str_replace('../', '', $row['ruta_archivo']);
        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }
    }
    $stmt->close();

    // Borrar el registro de la base de datos
    $stmtDel = $con->prepare("DELETE FROM documentos WHERE id_documento = ?");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
    $stmtDel->close();
}

header('Location: ../pages/administracion-de-documentos.php');
exit;
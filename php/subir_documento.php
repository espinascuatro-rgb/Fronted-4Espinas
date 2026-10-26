<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo-subir'])) {
    $nombrePersonalizado = trim($_POST['nombre_personalizado'] ?? '');
    $archivo = $_FILES['archivo-subir'];

    if ($archivo['error'] === UPLOAD_ERR_OK && !empty($nombrePersonalizado)) {
        $nombreOriginal = basename($archivo['name']);
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        
        // Carpeta destino donde se guardarán físicamente los archivos
        $carpetaDestino = __DIR__ . '/../uploads/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        // Generar un nombre único para evitar colisiones en el servidor
        $nombreUnico = uniqid('doc_', true) . '.' . $extension;
        $rutaFisica = $carpetaDestino . $nombreUnico;
        $rutaRelativa = '../uploads/' . $nombreUnico;

        if (move_uploaded_file($archivo['tmp_name'], $rutaFisica)) {
            $stmt = $con->prepare("INSERT INTO documentos (nombre_original, nombre_personalizado, tipo_archivo, ruta_archivo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombreOriginal, $nombrePersonalizado, $extension, $rutaRelativa);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header('Location: ../pages/administracion-de-documentos.php');
exit;
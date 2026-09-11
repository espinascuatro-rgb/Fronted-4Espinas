<?php
require_once 'conexion.php';
date_default_timezone_set('America/Montevideo');

header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = $_POST['nombre'] ?? '';
    $telefono     = $_POST['telefono'] ?? '';
    $direccion    = $_POST['direccion'] ?? '';
    $CdS          = $_POST['CdS'] ?? '';
    $credenciales = $_POST['credencial'] ?? '';
    $Contrasena1  = $_POST['Contrasena1'] ?? '';
    $cedula       = $_POST['cedula'] ?? '';

    $hash = password_hash($Contrasena1, PASSWORD_BCRYPT);

    $con->begin_transaction();

    try {
        $sqlPersona = "INSERT INTO persona (cedula, carne_de_salud, Numero_de_telefono, direccion) VALUES (?, ?, ?, ?)";
        $stmt1 = $con->prepare($sqlPersona);
        $stmt1->bind_param("ssss", $cedula, $CdS, $telefono, $direccion);
        $stmt1->execute();
        $stmt1->close();

        $sqlFuncionario = "INSERT INTO funcionario (usuario, credenciales, contrasenia, id_tipo) VALUES (?, ?, ?, ?)";
        $stmt2 = $con->prepare($sqlFuncionario);

        $stmt2->bind_param("ssss", $nombre, $credenciales, $hash, $cedula); 
        $stmt2->execute();
        $stmt2->close();

        $con->commit();

        echo json_encode([
            'status' => 'success',
            'message' => '¡Registro guardado correctamente!'
        ]);

    } catch (mysqli_sql_exception $exception) {
        $con->rollback();

        echo json_encode([
            'status' => 'error',
            'message' => 'Error al guardar el registro: ' . $exception->getMessage()
        ]);
    }
}

$con->close();
?>
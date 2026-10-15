<?php
require_once 'conexion.php';
date_default_timezone_set('America/Montevideo');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_equipo_medico = $_POST['id_equipo_medico'] ?? '';
    $nombre        = $_POST['nombre'] ?? '';
    $insumos       = $_POST['insumos'] ?? '';
    $insumo_t      = $_POST['insumo_t'] ?? '';
    $nombre        = $_POST['origen'] ?? '';
    $destino       = $_POST['destino'] ?? '';

    $con->begin_transaction();

    try {
        $sqlequipo_medico = "INSERT INTO ambulancia (id_equipo_medico, insumos) VALUES (?, ?)";
        $stmt = $con->prepare($sqlequipo_medico);
        $stmt->bind_param("sss", $id_equipo_medico, $insumos);
        $stmt->execute();
        $stmt->close();

        $sqltraslado_nobiologico = "INSERT INTO ambulancia (insumo_t) VALUES (?)";
        $stmt = $con->prepare($sqltraslado_nobiologico);
        $stmt->bind_param("sss", $insumo_t);
        $stmt->execute();
        $stmt->close();

        $sqltraslado = "INSERT INTO ambulancia (origen, destino) VALUES (?, ?)";
        $stmt = $con->prepare($sqltraslado);
        $stmt->bind_param("sss", $origen, $destino);
        $stmt->execute();
        $stmt->close();

        echo "¡Registro de insumos guardado correctamente!";

    } catch (mysqli_sql_exception $exception) {
        echo "Error al guardar el registro: " . $exception->getMessage();
    }
}

$con->close();
?>
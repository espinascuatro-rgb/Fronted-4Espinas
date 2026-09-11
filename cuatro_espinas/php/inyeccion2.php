<?php
require_once 'conexion.php';
date_default_timezone_set('America/Montevideo');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_de_serie = $_POST['numero_de_serie'] ?? '';
    $kilometraje   = $_POST['kilometraje'] ?? '';
    $matricula     = $_POST['matricula'] ?? '';

    try {
        $sqlambulancia = "INSERT INTO ambulancia (numero_de_serie, kilometraje, matricula) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sqlambulancia);
        $stmt->bind_param("sss", $numero_de_serie, $kilometraje, $matricula);
        $stmt->execute();
        $stmt->close();

        echo "¡Registro de ambulancia guardado correctamente!";

    } catch (mysqli_sql_exception $exception) {
        echo "Error al guardar el registro: " . $exception->getMessage();
    }
}

$con->close();
?>
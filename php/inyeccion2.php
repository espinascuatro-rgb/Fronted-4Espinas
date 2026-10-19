<?php
require_once 'conexion.php';
require_once 'checksesion.php';
date_default_timezone_set('America/Montevideo');

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if ($accion === 'guardar' || ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($accion))) {
    $numero_de_serie = trim($_POST['numero_de_serie'] ?? '');
    $kilometraje   = intval($_POST['kilometraje'] ?? 0);
    $matricula     = trim($_POST['matricula'] ?? '');

    if (empty($numero_de_serie)) {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("El número de serie es obligatorio."));
        exit;
    }

    try {
        $sql = "INSERT INTO ambulancia (numero_de_serie, kilometraje, matricula) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sis", $numero_de_serie, $kilometraje, $matricula);
        $stmt->execute();
        $stmt->close();

        header("Location: ../pages/registro-ambulancia.php?msg=guardado");
    } catch (mysqli_sql_exception $e) {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("Error al guardar: " . $e->getMessage()));
    }

} elseif ($accion === 'editar') {
    $serie_original = trim($_POST['serie_original'] ?? '');
    $kilometraje   = intval($_POST['kilometraje'] ?? 0);
    $matricula     = trim($_POST['matricula'] ?? '');

    try {
        $sql = "UPDATE ambulancia SET kilometraje = ?, matricula = ? WHERE numero_de_serie = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iss", $kilometraje, $matricula, $serie_original);
        $stmt->execute();
        $stmt->close();

        header("Location: ../pages/registro-ambulancia.php?msg=editado");
    } catch (mysqli_sql_exception $e) {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("Error al editar: " . $e->getMessage()));
    }

// 3. ELIMINAR AMBULANCIA
} elseif ($accion === 'eliminar') {
    $numero_de_serie = trim($_GET['numero_de_serie'] ?? '');

    try {
        $sql = "DELETE FROM ambulancia WHERE numero_de_serie = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $numero_de_serie);
        $stmt->execute();
        $stmt->close();

        header("Location: ../pages/registro-ambulancia.php?msg=eliminado");
    } catch (mysqli_sql_exception $e) {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("No se puede eliminar la ambulancia porque posee insumos o traslados asociados."));
    }
}

$con->close();
?>
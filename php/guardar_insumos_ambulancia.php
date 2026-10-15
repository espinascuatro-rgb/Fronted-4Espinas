<?php
require_once __DIR__ . '/checksesion.php';

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if ($accion === 'obtener_insumos') {
    $numero_de_serie = $conexion->real_escape_string($_GET['numero_de_serie'] ?? '');

    $sql = "SELECT * FROM insumo_ambulancia WHERE numero_de_serie_ambulancia = '$numero_de_serie'";
    $res = $conexion->query($sql);

    if ($res && $res->num_rows > 0) {
        echo json_encode(['status' => 'ok', 'data' => $res->fetch_assoc()]);
    } else {
        echo json_encode([
            'status' => 'ok', 
            'data' => [
                'vendas' => 0, 'jeringas' => 0, 'equipos_infusion' => 0,
                'guantes' => 0, 'mascarillas' => 0, 'hisopos' => 0,
                'desinfectantes' => 0, 'termometros' => 0, 'hilos_quirurgicos' => 0
            ]
        ]);
    }
    exit;
}

if ($accion === 'guardar_insumos') {
    $numero_de_serie = $conexion->real_escape_string($_POST['numero_de_serie_ambulancia'] ?? '');
    $vendas = intval($_POST['vendas'] ?? 0);
    $jeringas = intval($_POST['jeringas'] ?? 0);
    $equipos_infusion = intval($_POST['equipos_infusion'] ?? 0);
    $guantes = intval($_POST['guantes'] ?? 0);
    $mascarillas = intval($_POST['mascarillas'] ?? 0);
    $hisopos = intval($_POST['hisopos'] ?? 0);
    $desinfectantes = intval($_POST['desinfectantes'] ?? 0);
    $termometros = intval($_POST['termometros'] ?? 0);
    $hilos_quirurgicos = intval($_POST['hilos_quirurgicos'] ?? 0);

    if (empty($numero_de_serie)) {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("Debe seleccionar una ambulancia."));
        exit;
    }

    $sql = "INSERT INTO insumo_ambulancia 
            (numero_de_serie_ambulancia, vendas, jeringas, equipos_infusion, guantes, mascarillas, hisopos, desinfectantes, termometros, hilos_quirurgicos) 
            VALUES 
            ('$numero_de_serie', $vendas, $jeringas, $equipos_infusion, $guantes, $mascarillas, $hisopos, $desinfectantes, $termometros, $hilos_quirurgicos)
            ON DUPLICATE KEY UPDATE 
            vendas = $vendas,
            jeringas = $jeringas,
            equipos_infusion = $equipos_infusion,
            guantes = $guantes,
            mascarillas = $mascarillas,
            hisopos = $hisopos,
            desinfectantes = $desinfectantes,
            termometros = $termometros,
            hilos_quirurgicos = $hilos_quirurgicos";

    if ($conexion->query($sql)) {
        header("Location: ../pages/registro-ambulancia.php?msg=insumos_guardados");
    } else {
        header("Location: ../pages/registro-ambulancia.php?err=" . urlencode("Error al guardar insumos: " . $conexion->error));
    }
}

$conexion->close();
<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servidor = "localhost";
$usuario  = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    echo json_encode(['status' => 'error', 'msg' => 'Error de conexión: ' . $conexion->connect_error]);
    exit;
}

$conexion->set_charset("utf8mb4");
$accion = $_REQUEST['accion'] ?? '';

// 1. Guardar ubicación del chofer desde el móvil
if ($accion === 'actualizar_chofer') {
    $id_traslado = isset($_POST['id_traslado']) ? intval($_POST['id_traslado']) : 1;
    $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : null;
    $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : null;

    if ($lat !== null && $lng !== null) {
        $stmt = $conexion->prepare("UPDATE traslado SET lat_chofer = ?, lng_chofer = ? WHERE id_traslado = ?");
        $stmt->bind_param("ddi", $lat, $lng, $id_traslado);
        $stmt->execute();
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Coordenadas nulas']);
    }

// 2. Guardar o actualizar la dirección/coordenadas de destino
} elseif ($accion === 'actualizar_destino') {
    $id_traslado = isset($_POST['id_traslado']) ? intval($_POST['id_traslado']) : 1;
    $direccion = $_POST['direccion'] ?? '';
    $lat_dest = isset($_POST['lat_dest']) ? floatval($_POST['lat_dest']) : null;
    $lng_dest = isset($_POST['lng_dest']) ? floatval($_POST['lng_dest']) : null;

    $stmt = $conexion->prepare("UPDATE traslado SET destino = ? WHERE id_traslado = ?");
    $stmt->bind_param("si", $direccion, $id_traslado);
    $stmt->execute();

    echo json_encode(['status' => 'ok']);

// 3. Obtener el estado del traslado en tiempo real
} elseif ($accion === 'obtener_estado') {
    $id_traslado = isset($_GET['id_traslado']) ? intval($_GET['id_traslado']) : 1;

    $sql = "SELECT * FROM traslado WHERE id_traslado = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_traslado);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        echo json_encode(['status' => 'ok', 'data' => $res]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'No existe traslado con ID ' . $id_traslado]);
    }

// 4. Cambiar estado: Iniciar o Finalizar
} elseif ($accion === 'cambiar_estado') {
    $id_traslado = isset($_POST['id_traslado']) ? intval($_POST['id_traslado']) : 1;
    $estado = $_POST['estado'] ?? 'en_proceso';
    $transporte = $_POST['transporte'] ?? 'drive';
    $hora_actual = date('H:i:s');

    if ($estado === 'en_proceso') {
        $stmt = $conexion->prepare("UPDATE traslado SET estado = ?, transporte = ?, hora_de_salida = ?, hora_de_llegada = NULL WHERE id_traslado = ?");
        $stmt->bind_param("sssi", $estado, $transporte, $hora_actual, $id_traslado);
    } else {
        $stmt = $conexion->prepare("UPDATE traslado SET estado = ?, hora_de_llegada = ? WHERE id_traslado = ?");
        $stmt->bind_param("ssi", $estado, $hora_actual, $id_traslado);
    }

    $stmt->execute();
    echo json_encode(['status' => 'ok']);
}

$conexion->close();
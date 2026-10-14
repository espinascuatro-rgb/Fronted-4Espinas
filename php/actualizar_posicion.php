<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json; charset=utf-8');

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
$id_traslado = isset($_REQUEST['id_traslado']) ? intval($_REQUEST['id_traslado']) : 1;

// 1. Guardar Chofer (Cédula), Acompañante y Ambulancia (Matrícula)
if ($accion === 'guardar_datos_chofer') {
    $chofer = $conexion->real_escape_string($_POST['chofer'] ?? '');
    $acompanante = $conexion->real_escape_string($_POST['acompanante'] ?? '');
    $ambulancia = $conexion->real_escape_string($_POST['ambulancia'] ?? '');

    $sql = "UPDATE traslado SET cedula_funcionario = '$chofer', acompanante = '$acompanante', matricula_ambulancia = '$ambulancia' WHERE id_traslado = $id_traslado";

    if ($conexion->query($sql)) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $conexion->error]);
    }

// 2. Guardar ubicación GPS del Chofer
} elseif ($accion === 'actualizar_chofer') {
    $lat = floatval($_POST['lat'] ?? 0);
    $lng = floatval($_POST['lng'] ?? 0);

    if ($lat != 0 && $lng != 0) {
        $sql = "UPDATE traslado SET lat_chofer = $lat, lng_chofer = $lng WHERE id_traslado = $id_traslado";
        $conexion->query($sql);
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Coordenadas inválidas']);
    }

// 3. Guardar Hora Estimada de Llegada según GPS
} elseif ($accion === 'actualizar_tiempo_gps') {
    $hora_estimada = $conexion->real_escape_string($_POST['hora_estimada'] ?? '');

    $sql = "UPDATE traslado SET hora_estimada_gps = '$hora_estimada' WHERE id_traslado = $id_traslado";
    $conexion->query($sql);
    echo json_encode(['status' => 'ok']);

// 4. Guardar Dirección de Destino cambiada desde el Mapa
} elseif ($accion === 'actualizar_destino') {
    $direccion = $conexion->real_escape_string($_POST['direccion'] ?? '');

    $sql = "UPDATE traslado SET destino = '$direccion' WHERE id_traslado = $id_traslado";
    $conexion->query($sql);
    echo json_encode(['status' => 'ok']);

// 5. Consultar todo el estado cruzando la cédula con la tabla funcionario
} elseif ($accion === 'obtener_estado') {
    $sql = "SELECT t.*, f.usuario AS nombre_usuario_chofer 
            FROM traslado t 
            LEFT JOIN funcionario f ON t.cedula_funcionario = f.id_tipo 
            WHERE t.id_traslado = $id_traslado";

    $res = $conexion->query($sql);
    $datos = $res ? $res->fetch_assoc() : null;

    if ($datos) {
        echo json_encode(['status' => 'ok', 'data' => $datos]);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'No encontrado']);
    }

// 6. Cambiar Estado del Traslado (Iniciar / Finalizar)
} elseif ($accion === 'cambiar_estado') {
    $estado = $conexion->real_escape_string($_POST['estado'] ?? 'en_proceso');
    $transporte = $conexion->real_escape_string($_POST['transporte'] ?? 'drive');
    $hora_actual = date('H:i:s');

    if ($estado === 'en_proceso') {
        $sql = "UPDATE traslado SET estado = '$estado', transporte = '$transporte', hora_de_salida = '$hora_actual', hora_de_llegada = NULL WHERE id_traslado = $id_traslado";
    } else {
        $sql = "UPDATE traslado SET estado = '$estado', hora_de_llegada = '$hora_actual' WHERE id_traslado = $id_traslado";
    }

    $conexion->query($sql);
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'msg' => 'Acción inválida']);
}

$conexion->close();
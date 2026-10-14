<?php
// Permitir respuestas JSON limpias sin advertencias HTML
header('Content-Type: application/json');

// Si usas sesiones, iniciamos sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Conexión a la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = "";
$base_datos = "hospital_de_clinicas";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    echo json_encode(['status' => 'error', 'msg' => 'Error de conexión: ' . $conexion->connect_error]);
    exit;
}

// Configurar juego de caracteres
$conexion->set_charset("utf8mb4");

$accion = $_REQUEST['accion'] ?? '';

// --- ACCIÓN 1: EL CHOFER ENVÍA SU UBICACIÓN GPS ---
if ($accion === 'actualizar_chofer') {
    $id_traslado = isset($_POST['id_traslado']) ? intval($_POST['id_traslado']) : 0;
    $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : null;
    $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : null;

    if ($id_traslado > 0 && $lat !== null && $lng !== null) {
        $stmt = $conexion->prepare("UPDATE traslado SET lat_chofer = ?, lng_chofer = ? WHERE id_traslado = ?");
        
        if ($stmt) {
            $stmt->bind_param("ddi", $lat, $lng, $id_traslado);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'ok', 'msg' => 'Ubicación actualizada']);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'Error al ejecutar SQL: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error en la consulta: ' . $conexion->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Datos incompletos recibidos']);
    }

// --- ACCIÓN 2: EL ADMINISTRATIVO CONSULTA EL ESTADO DE LA UBICACIÓN ---
} elseif ($accion === 'obtener_estado') {
    $id_traslado = isset($_GET['id_traslado']) ? intval($_GET['id_traslado']) : 0;

    if ($id_traslado > 0) {
        $sql = "SELECT t.*, f.usuario AS nombre_chofer, a.matricula 
                FROM traslado t 
                LEFT JOIN funcionario f ON t.cedula_funcionario = f.id_tipo 
                LEFT JOIN ambulancia a ON t.matricula_ambulancia = a.numero_de_serie 
                WHERE t.id_traslado = ?";

        $stmt = $conexion->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_traslado);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $datos = $resultado->fetch_assoc();

            if ($datos) {
                echo json_encode(['status' => 'ok', 'data' => $datos]);
            } else {
                echo json_encode(['status' => 'error', 'msg' => 'No se encontró el traslado']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error en consulta de estado']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'ID de traslado inválido']);
    }

// --- ACCIÓN 3: CAMBIAR ESTADO (INICIAR O FINALIZAR) ---
} elseif ($accion === 'cambiar_estado') {
    $id_traslado = isset($_POST['id_traslado']) ? intval($_POST['id_traslado']) : 0;
    $estado = $_POST['estado'] ?? '';
    $transporte = $_POST['transporte'] ?? 'drive';

    if ($id_traslado > 0 && in_array($estado, ['en_proceso', 'finalizado'])) {
        $hora_actual = date('H:i:s');

        if ($estado === 'en_proceso') {
            $stmt = $conexion->prepare("UPDATE traslado SET estado = ?, transporte = ?, hora_de_salida = ? WHERE id_traslado = ?");
            $stmt->bind_param("sssi", $estado, $transporte, $hora_actual, $id_traslado);
        } else {
            $stmt = $conexion->prepare("UPDATE traslado SET estado = ?, hora_de_llegada = ? WHERE id_traslado = ?");
            $stmt->bind_param("ssi", $estado, $hora_actual, $id_traslado);
        }

        if ($stmt && $stmt->execute()) {
            echo json_encode(['status' => 'ok', 'msg' => 'Estado actualizado']);
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error al cambiar estado']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Parámetros de estado inválidos']);
    }

} else {
    echo json_encode(['status' => 'error', 'msg' => 'Acción no reconocida']);
}

$conexion->close();
<?php
require_once __DIR__ . '/checksesion.php';
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $numero_de_serie = trim($_GET['numero_de_serie'] ?? '');

    if (!empty($numero_de_serie)) {
        $con->begin_transaction();

        try {
            $stmt_traslados = $con->prepare("DELETE FROM traslado WHERE matricula_ambulancia = ?");
            if (!$stmt_traslados) {
                throw new Exception("Error al preparar la consulta de traslados");
            }
            $stmt_traslados->bind_param("s", $numero_de_serie);
            $stmt_traslados->execute();
            $stmt_traslados->close();
            $stmt_ambulancia = $con->prepare("DELETE FROM ambulancia WHERE numero_de_serie = ?");
            if (!$stmt_ambulancia) {
                throw new Exception("Error al preparar la consulta de ambulancia");
            }
            $stmt_ambulancia->bind_param("s", $numero_de_serie);
            $stmt_ambulancia->execute();
            $stmt_ambulancia->close();
            $con->commit();
            header('Location: ../pages/registro-ambulancia.php?msg=eliminado');
            exit;

        } catch (Exception $e) {
            $con->rollback();
            header('Location: ../pages/registro-ambulancia.php?err=No_se_pudo_eliminar_la_ambulancia_y_sus_registros');
            exit;
        }
    }
    
    header('Location: ../pages/registro-ambulancia.php?err=No_se_pudo_eliminar');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? 'guardar';
    $numero_de_serie = trim($_POST['numero_de_serie'] ?? '');
    $serie_original = trim($_POST['serie_original'] ?? '');
    $kilometraje = trim($_POST['kilometraje'] ?? '');
    $matricula = trim($_POST['matricula'] ?? '');
    $nombre_solicitante = trim($_POST['nombre_solicitante'] ?? '');
    $telefono_solicitante = trim($_POST['telefono_solicitante'] ?? '');
    $nombre_paciente = trim($_POST['nombre_paciente'] ?? '');
    $edad_paciente = !empty($_POST['edad_paciente']) ? (int)$_POST['edad_paciente'] : null;
    $condicion_paciente = trim($_POST['condicion_paciente'] ?? '');
    $origen = trim($_POST['origen'] ?? '');
    $destino = trim($_POST['destino'] ?? '');

    if (empty($numero_de_serie)) {
        header('Location: ../pages/registro-ambulancia.php?err=El_numero_de_serie_es_obligatorio');
        exit;
    }

    if ($accion === 'editar') {
        $idAUsar = !empty($serie_original) ? $serie_original : $numero_de_serie;

        $stmt = $con->prepare("UPDATE ambulancia SET numero_de_serie = ?, kilometraje = ?, matricula = ? WHERE numero_de_serie = ?");
        if ($stmt) {
            $stmt->bind_param("ssss", $numero_de_serie, $kilometraje, $matricula, $idAUsar);
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: ../pages/registro-ambulancia.php?msg=editado');
                exit;
            } else {
                $error = urlencode($stmt->error);
                $stmt->close();
                header('Location: ../pages/registro-ambulancia.php?err=' . $error);
                exit;
            }
        } else {
            header('Location: ../pages/registro-ambulancia.php?err=Error_en_preparacion_SQL');
            exit;
        }

    } else {
        $stmt = $con->prepare("INSERT INTO ambulancia (numero_de_serie, kilometraje, matricula) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $numero_de_serie, $kilometraje, $matricula);
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: ../pages/registro-ambulancia.php?msg=guardado');
                exit;
            } else {
                $error = urlencode($stmt->error);
                $stmt->close();
                header('Location: ../pages/registro-ambulancia.php?err=' . $error);
                exit;
            }
        } else {
            header('Location: ../pages/registro-ambulancia.php?err=Error_en_insercion_SQL');
            exit;
        }
    }
}
<?php
session_start();
require_once 'checksesion.php'; 
require_once 'conexion.php'; 

date_default_timezone_set('America/Montevideo');
header('Content-Type: application/json');
// dale, te reto a intentar entender todo esto.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_SESSION['usuario_id'] ?? '';

    if (empty($cedula)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesión inválida o expirada.'
        ]);
        exit;
    }

    try {
        $con->begin_transaction();
        $actualizado = false;

        if (isset($_POST['nombre'])) {
            $nombre = trim($_POST['nombre']);
            $sql = "UPDATE funcionario SET usuario = ? WHERE id_tipo = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $nombre, $cedula);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['usuario_nombre'] = $nombre;
            $actualizado = true;
        }

        if (isset($_POST['telefono'])) {
            $telefono = trim($_POST['telefono']);
            $sql = "UPDATE persona SET Numero_de_telefono = ? WHERE cedula = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $telefono, $cedula);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['telefono'] = $telefono;
            $actualizado = true;
        }

        if (isset($_POST['direccion'])) {
            $direccion = trim($_POST['direccion']);
            $sql = "UPDATE persona SET direccion = ? WHERE cedula = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $direccion, $cedula);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['direccion'] = $direccion;
            $actualizado = true;
        }
// pilas que vas bien.
        if (isset($_POST['credencial'])) {
            $credencial = trim($_POST['credencial']);
            $sql = "UPDATE funcionario SET credenciales = ? WHERE id_tipo = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $credencial, $cedula);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['credencial'] = $credencial;
            $actualizado = true;
        }

        if (isset($_POST['password_actual']) && isset($_POST['password_nueva'])) {
            $password_actual = $_POST['password_actual'];
            $password_nueva = $_POST['password_nueva'];

            if (!empty($password_actual)) {
                $sqlCheck = "SELECT contrasenia FROM funcionario WHERE id_tipo = ?";
                $stmtPass = $con->prepare($sqlCheck);
                $stmtPass->bind_param("s", $cedula);
                $stmtPass->execute();
                $result = $stmtPass->get_result();
                $userBD = $result->fetch_assoc();
                $stmtPass->close();

                if (!$userBD || !password_verify($password_actual, $userBD['contrasenia'])) {
                    $con->rollback();
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'La contraseña anterior ingresada es incorrecta.'
                    ]);
                    exit;
                }

                if (!empty($password_nueva)) {
                    $nuevoHash = password_hash($password_nueva, PASSWORD_BCRYPT);
                    $sqlFuncionario = "UPDATE funcionario SET contrasenia = ? WHERE id_tipo = ?";
                    $stmt2 = $con->prepare($sqlFuncionario);
                    $stmt2->bind_param("ss", $nuevoHash, $cedula);
                    $stmt2->execute();
                    $stmt2->close();
                    
                    $actualizado = true;
                } else {
                    $con->rollback();
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Debes ingresar una nueva contraseña.'
                    ]);
                    exit;
                }
            }
        }
//Esto es muy complicado para vos pive
        $con->commit();

        if ($actualizado) {
            echo json_encode([
                'status' => 'success',
                'message' => '¡Dato actualizado correctamente!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se recibieron datos para procesar.'
            ]);
        }

    } catch (mysqli_sql_exception $exception) {
        $con->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar: ' . $exception->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de solicitud no permitido.'
    ]);
}
//dejaselo a la gente grande, pive.
$con->close();
?>
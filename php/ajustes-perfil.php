<?php
session_start();
require_once 'checksesion.php'; 
require_once 'conexion.php'; 

date_default_timezone_set('America/Montevideo');
header('Content-Type: application/json');

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

    $nombre          = $_POST['nombre'] ?? '';
    $telefono        = $_POST['telefono'] ?? '';
    $direccion       = $_POST['direccion'] ?? '';
    $credenciales    = $_POST['credencial'] ?? '';
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nueva  = $_POST['password_nueva'] ?? '';

    $con->begin_transaction();

    try {
        $sqlPersona = "UPDATE persona SET Numero_de_telefono = ?, direccion = ? WHERE cedula = ?";
        $stmt1 = $con->prepare($sqlPersona);
        $stmt1->bind_param("sss", $telefono, $direccion, $cedula);
        $stmt1->execute();
        $stmt1->close();

        if (!empty($password_actual)) {
            $sqlCheckPass = "UPDATE_CHECK" ; 
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

                $sqlFuncionario = "UPDATE funcionario SET usuario = ?, credenciales = ?, contrasenia = ? WHERE id_tipo = ?";
                $stmt2 = $con->prepare($sqlFuncionario);
                $stmt2->bind_param("ssss", $nombre, $credenciales, $nuevoHash, $cedula);
                $stmt2->execute();
                $stmt2->close();
            } else {
                // Caso en que puso la actual pero no la nueva
                $con->rollback();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Debes ingresar una nueva contraseña.'
                ]);
                exit;
            }
        } else {
            // Actualizar funcionario sin modificar la contraseña
            $sqlFuncionario = "UPDATE funcionario SET usuario = ?, credenciales = ? WHERE id_tipo = ?";
            $stmt2 = $con->prepare($sqlFuncionario);
            $stmt2->bind_param("sss", $nombre, $credenciales, $cedula);
            $stmt2->execute();
            $stmt2->close();
        }

        $con->commit();

        $_SESSION['nombre']     = $nombre;
        $_SESSION['telefono']   = $telefono;
        $_SESSION['direccion']  = $direccion;
        $_SESSION['credencial'] = $credenciales;

        echo json_encode([
            'status' => 'success',
            'message' => '¡Datos actualizados correctamente!'
        ]);

    } catch (mysqli_sql_exception $exception) {
        $con->rollback();

        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar el perfil: ' . $exception->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de solicitud no permitido.'
    ]);
}

$con->close();
?>
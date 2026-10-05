<?php
session_start(); // Inicia la sesión para poder almacenar informacion del usuario autenticado.
require_once 'conexion.php';

header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario     = $_POST['usuario'] ?? '';
    $contrasenia = $_POST['contrasenia'] ?? '';
    $cedula      = $_POST['cedula'] ?? '';

    if (empty($usuario) || empty($contrasenia) || empty($cedula)) { // Verifica que los campos no esten vacios.
        echo json_encode([ // Devuelve un json con el status y el mensaje para que el js lo procese y muestre en el frontend.
            'status' => 'error',
            'message' => 'Por favor, completa todos los campos.'
        ]);
        exit();
    }

    try {
        $sql = "SELECT f.usuario, f.contrasenia, p.cedula     
                FROM funcionario f
                INNER JOIN persona p ON f.id_tipo = p.cedula
                WHERE f.usuario = ? AND p.cedula = ?"; //se establece una consulta para verificar datos del usuario.
                
        $stmt = $con->prepare($sql); //consulta preparada igual que en inserciont.
        $stmt->bind_param("ss", $usuario, $cedula);
        $stmt->execute();
        $resultado = $stmt->get_result(); //obtiene el resultado de la consulta.

        if ($row = $resultado->fetch_assoc()) { //si hay un resultado, se verifica la contraseña.
            if (password_verify($contrasenia, $row['contrasenia'])) { //comprueba si coincide la contra con la de BS
                session_regenerate_id(true); //genera un nuevo id de sesion para evitar hackeos.

                $_SESSION['usuario_id']     = $row['cedula']; 
                $_SESSION['usuario_nombre'] = $row['usuario'];
                $_SESSION['autenticado']    = true; 

                echo json_encode([ // igual que en inserciont devuelve un json para el js.
                    'status' => 'success',
                    'redirect' => 'pages/guia.php'
                ]);
                exit();
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Contraseña incorrecta.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Usuario o cédula no válidos.'
            ]);
        }

        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la autenticación: ' . $e->getMessage()
        ]);
    }
}

$con->close();
?>
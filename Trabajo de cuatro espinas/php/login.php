<?php
session_start();
require_once 'conexion.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario     = $_POST['usuario'] ?? '';
    $contrasenia = $_POST['contrasenia'] ?? '';
    $cedula      = $_POST['cedula'] ?? '';

    if (empty($usuario) || empty($contrasenia) || empty($cedula)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    try {
        $sql = "SELECT f.usuario, f.contrasenia, p.cedula 
                FROM funcionario f
                INNER JOIN persona p ON f.id_tipo = p.cedula
                WHERE f.usuario = ? AND p.cedula = ?";
                
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $usuario, $cedula);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            if (password_verify($contrasenia, $row['contrasenia'])) {
                session_regenerate_id(true);

                $_SESSION['usuario_id']     = $row['cedula'];
                $_SESSION['usuario_nombre'] = $row['usuario'];
                $_SESSION['autenticado']    = true;

                header("Location: ../pages/guia.php"); 
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Usuario o cédula no válidos.";
        }

        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        echo "Error en la autenticación: " . $e->getMessage();
    }
}

$con->close();
?>
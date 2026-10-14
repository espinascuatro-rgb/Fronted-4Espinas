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
$hoy = date('Y-m-d');

// 1. AGREGAR PACIENTE
if ($accion === 'agregar') {
    $ci = intval($_POST['ci']);
    $nombre = $conexion->real_escape_string(trim($_POST['nombre']));
    $apellido = $conexion->real_escape_string(trim($_POST['apellido']));
    $fecha_nac = $conexion->real_escape_string($_POST['fecha_nac']);
    $sexo = $conexion->real_escape_string($_POST['sexo']);

    // Validación de fecha de nacimiento
    if ($fecha_nac > $hoy) {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("La fecha de nacimiento no puede ser posterior a la fecha actual ($hoy)."));
        exit;
    }

    if ($ci <= 0 || empty($nombre) || empty($apellido)) {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Por favor, complete todos los campos obligatorios."));
        exit;
    }

    $sql = "INSERT INTO persona (ci, nombre, apellido, fecha_nac, sexo) 
            VALUES ($ci, '$nombre', '$apellido', '$fecha_nac', '$sexo')";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=agregado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al guardar en MySQL: " . $conexion->error));
    }

// 2. EDITAR PACIENTE
} elseif ($accion === 'editar') {
    $ci_original = intval($_POST['ci_original']);
    $nombre = $conexion->real_escape_string(trim($_POST['nombre']));
    $apellido = $conexion->real_escape_string(trim($_POST['apellido']));
    $fecha_nac = $conexion->real_escape_string($_POST['fecha_nac']);
    $sexo = $conexion->real_escape_string($_POST['sexo']);

    // Validación de fecha de nacimiento
    if ($fecha_nac > $hoy) {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("La fecha de nacimiento no puede ser posterior a la fecha actual ($hoy)."));
        exit;
    }

    $sql = "UPDATE persona 
            SET nombre = '$nombre', apellido = '$apellido', fecha_nac = '$fecha_nac', sexo = '$sexo' 
            WHERE ci = $ci_original";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=editado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al actualizar: " . $conexion->error));
    }

// 3. ELIMINAR PACIENTE
} elseif ($accion === 'eliminar') {
    $ci = intval($_GET['ci']);

    $sql = "DELETE FROM persona WHERE ci = $ci";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=eliminado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("No se puede eliminar el paciente porque tiene registros vinculados en el hospital."));
    }
}

$conexion->close();
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

// 1. AGREGAR PACIENTE (PERSONA)
if ($accion === 'agregar') {
    $cedula = $conexion->real_escape_string(trim($_POST['cedula']));
    $primer_nombre = $conexion->real_escape_string(trim($_POST['primer_nombre']));
    $segundo_nombre = $conexion->real_escape_string(trim($_POST['segundo_nombre']));
    $primer_apellido = $conexion->real_escape_string(trim($_POST['primer_apellido']));
    $segundo_apellido = $conexion->real_escape_string(trim($_POST['segundo_apellido']));
    $carne_de_salud = $conexion->real_escape_string(trim($_POST['carne_de_salud']));
    $numero_de_telefono = $conexion->real_escape_string(trim($_POST['numero_de_telefono']));
    $direccion = $conexion->real_escape_string(trim($_POST['direccion']));

    if (empty($cedula) || empty($primer_nombre) || empty($primer_apellido)) {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("La cédula, el primer nombre y el primer apellido son obligatorios."));
        exit;
    }

    $sql = "INSERT INTO persona (cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, carne_de_salud, numero_de_telefono, direccion) 
            VALUES ('$cedula', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$carne_de_salud', '$numero_de_telefono', '$direccion')";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=agregado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al guardar: " . $conexion->error));
    }

// 2. EDITAR PACIENTE (PERSONA)
} elseif ($accion === 'editar') {
    $cedula_original = $conexion->real_escape_string(trim($_POST['cedula_original']));
    $primer_nombre = $conexion->real_escape_string(trim($_POST['primer_nombre']));
    $segundo_nombre = $conexion->real_escape_string(trim($_POST['segundo_nombre']));
    $primer_apellido = $conexion->real_escape_string(trim($_POST['primer_apellido']));
    $segundo_apellido = $conexion->real_escape_string(trim($_POST['segundo_apellido']));
    $carne_de_salud = $conexion->real_escape_string(trim($_POST['carne_de_salud']));
    $numero_de_telefono = $conexion->real_escape_string(trim($_POST['numero_de_telefono']));
    $direccion = $conexion->real_escape_string(trim($_POST['direccion']));

    $sql = "UPDATE persona 
            SET primer_nombre = '$primer_nombre', 
                segundo_nombre = '$segundo_nombre', 
                primer_apellido = '$primer_apellido', 
                segundo_apellido = '$segundo_apellido', 
                carne_de_salud = '$carne_de_salud', 
                numero_de_telefono = '$numero_de_telefono', 
                direccion = '$direccion' 
            WHERE cedula = '$cedula_original'";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=editado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al actualizar: " . $conexion->error));
    }

// 3. ELIMINAR PACIENTE (PERSONA)
} elseif ($accion === 'eliminar') {
    $cedula = $conexion->real_escape_string(trim($_GET['cedula']));

    $sql = "DELETE FROM persona WHERE cedula = '$cedula'";

    if ($conexion->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=eliminado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("No se puede eliminar el paciente porque posee registros o traslados asociados."));
    }
}

$conexion->close();
<?php
require_once 'conexion.php';

$con->set_charset("utf8mb4");

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if ($accion === 'agregar') {
    $cedula = $con->real_escape_string(trim($_POST['cedula']));
    $primer_nombre = $con->real_escape_string(trim($_POST['primer_nombre']));
    $segundo_nombre = $con->real_escape_string(trim($_POST['segundo_nombre']));
    $primer_apellido = $con->real_escape_string(trim($_POST['primer_apellido']));
    $segundo_apellido = $con->real_escape_string(trim($_POST['segundo_apellido']));
    $carne_de_salud = $con->real_escape_string(trim($_POST['carne_de_salud']));
    $numero_de_telefono = $con->real_escape_string(trim($_POST['numero_de_telefono']));
    $direccion = $con->real_escape_string(trim($_POST['direccion']));

    if (empty($cedula) || empty($primer_nombre) || empty($primer_apellido)) {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("La cédula, el primer nombre y el primer apellido son obligatorios."));
        exit;
    }

    $sql = "INSERT INTO persona (cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, carne_de_salud, numero_de_telefono, direccion) 
            VALUES ('$cedula', '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido', '$carne_de_salud', '$numero_de_telefono', '$direccion')";

    if ($con->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=agregado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al guardar: " . $con->error));
    }

} elseif ($accion === 'editar') {
    $cedula_original = $con->real_escape_string(trim($_POST['cedula_original']));
    $primer_nombre = $con->real_escape_string(trim($_POST['primer_nombre']));
    $segundo_nombre = $con->real_escape_string(trim($_POST['segundo_nombre']));
    $primer_apellido = $con->real_escape_string(trim($_POST['primer_apellido']));
    $segundo_apellido = $con->real_escape_string(trim($_POST['segundo_apellido']));
    $carne_de_salud = $con->real_escape_string(trim($_POST['carne_de_salud']));
    $numero_de_telefono = $con->real_escape_string(trim($_POST['numero_de_telefono']));
    $direccion = $con->real_escape_string(trim($_POST['direccion']));

    $sql = "UPDATE persona 
            SET primer_nombre = '$primer_nombre', 
                segundo_nombre = '$segundo_nombre', 
                primer_apellido = '$primer_apellido', 
                segundo_apellido = '$segundo_apellido', 
                carne_de_salud = '$carne_de_salud', 
                numero_de_telefono = '$numero_de_telefono', 
                direccion = '$direccion' 
            WHERE cedula = '$cedula_original'";

    if ($con->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=editado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("Error al actualizar: " . $con->error));
    }

} elseif ($accion === 'eliminar') {
    $cedula = $con->real_escape_string(trim($_GET['cedula']));

    $sql = "DELETE FROM persona WHERE cedula = '$cedula'";

    if ($con->query($sql)) {
        header("Location: ../pages/administracion-pacientes.php?msg=eliminado");
    } else {
        header("Location: ../pages/administracion-pacientes.php?err=" . urlencode("No se puede eliminar el paciente porque posee registros o traslados asociados."));
    }
}

$con->close();
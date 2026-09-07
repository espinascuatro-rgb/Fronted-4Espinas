<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = $_POST['nombre'];
    $telefono      = $_POST['telefono'];
    $direccion     = $_POST['direccion'];
    $CdS        = $_POST['CdS'];
    $credenciales = $_POST['credencial'];
    $Contrasena1  = $_POST['Contrasena1'];
    $cedula       = $_POST['cedula'];
    

    // Iniciamos una transacción para asegurar ambas inserciones
    $con->begin_transaction();

    try {
        // 1. PRIMER PASO: Insertar en la tabla PADRE ('persona')
        // (Ajusta los nombres de las columnas 'cedula', 'nombre', 'apellido' si difieren en tu BD)
        $sqlPersona = "INSERT INTO persona (cedula, carne_de_salud, Numero_de_telefono, direccion) VALUES (?, ?, ?, ?)";
        $stmt1 = $con->prepare($sqlPersona);
        $stmt1->bind_param("ssss", $cedula, $CdS, $telefono, $direccion);
        $stmt1->execute();
        $stmt1->close();

        // 2. SEGUNDO PASO: Insertar en la tabla HIJO ('funcionario')
        // Importante: Revisa si el campo en 'funcionario' es 'cedula' o 'id_tipo'
        $sqlFuncionario = "INSERT INTO funcionario (usuario, credenciales, contrasenia, id_tipo) VALUES (?, ?, ?, ?)";
        $stmt2 = $con->prepare($sqlFuncionario);
        $stmt2->bind_param("ssss", $nombre, $credenciales, $Contrasena1, $cedula);
        $stmt2->execute();
        $stmt2->close();

        // Si todo salió bien, confirmamos los cambios
        $con->commit();
        echo "¡Registro de persona y funcionario guardado correctamente!";

    } catch (mysqli_sql_exception $exception) {
        // Si ocurre un error (por ejemplo, si la cédula ya existe), revertimos los cambios
        $con->rollback();
        echo "Error al guardar el registro: " . $exception->getMessage();
    }
}

$con->close();
?>
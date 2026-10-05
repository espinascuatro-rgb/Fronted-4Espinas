<?php
require_once 'conexion.php'; // llama a la conexion.
date_default_timezone_set('America/Montevideo'); // establece zona horaria.

header('Content-Type: application/json'); // Hace que el script responda en un json para el js pueda procesarlo y mostrarlo en el frontend.

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Hace que se interrumpa la ejecucion del script si hay un error en la bs y se lanza una excepcion

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // verifica que lo enviado del form sea post.
    $nombre       = $_POST['nombre'] ?? ''; // las variables. Las ?? sirven para que si no se envia un valor, se le asigne un string vacio.
    $telefono     = $_POST['telefono'] ?? '';
    $direccion    = $_POST['direccion'] ?? '';
    $CdS          = $_POST['CdS'] ?? '';
    $credenciales = $_POST['credencial'] ?? '';
    $Contrasena1  = $_POST['Contrasena1'] ?? '';
    $cedula       = $_POST['cedula'] ?? '';

    $hash = password_hash($Contrasena1, PASSWORD_BCRYPT); // encripta la contraseña para que no se guarde en texto plano. 

    $con->begin_transaction(); // inicia una transaccion entre tablas., si hay error se hace rollback y no guarda en BdS.

    try { // captura errores y los muestra
        $sqlPersona = "INSERT INTO persona (cedula, carne_de_salud, Numero_de_telefono, direccion) VALUES (?, ?, ?, ?)"; // hace una varible con la consulta sql para insertar en la tabla persona.
        $stmt1 = $con->prepare($sqlPersona); // prepara la consulta para que se ejecute de manera segura y no haya inyeccion sql.
        $stmt1->bind_param("ssss", $cedula, $CdS, $telefono, $direccion); // hace que los valores de las variables se asignen a los parametros de la consulta sql. Los "ssss" indican que los parametros son caracteres.
        $stmt1->execute(); // ejecuta la consulta.
        $stmt1->close(); // cierra la consulta.

        $sqlFuncionario = "INSERT INTO funcionario (usuario, credenciales, contrasenia, id_tipo) VALUES (?, ?, ?, ?)";
        $stmt2 = $con->prepare($sqlFuncionario);

        $stmt2->bind_param("ssss", $nombre, $credenciales, $hash, $cedula); 
        $stmt2->execute();
        $stmt2->close();

        $con->commit(); // guarda en la base de datos los cambios del transaction.

        echo json_encode([ // devuelve un json con el status y el mensaje para que el js lo procese y muestre en el frontend.
            'status' => 'success',
            'message' => '¡Registro guardado correctamente!'
        ]);

    } catch (mysqli_sql_exception $exception) { // captura el error y hace rollback para que no se guarde en la bs.
        $con->rollback();

        echo json_encode([
            'status' => 'error',
            'message' => 'Error al guardar el registro: ' . $exception->getMessage()
        ]);
    }
}

$con->close(); // cierra la conexion a la bs.
?>
<?php
require_once 'config.php'; // llama a las constante de las credenciales de la bs.

$con = new mysqli(BDhost, BDuser, BDpass, BDname); // hace la conexion a la bs con las constantes

if($con->connect_error){ //si hay error y se detiene la conexion.
    die("error: " . $con->connect_error);
}
?>
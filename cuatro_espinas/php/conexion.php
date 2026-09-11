<?php
require_once 'config.php';

$con = new mysqli(BDhost, BDuser, BDpass, BDname);

if($con->connect_error){
    die("error: " . $con->connect_error);
}
?>
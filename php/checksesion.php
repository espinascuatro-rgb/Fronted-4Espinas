<?php
if (session_status() === PHP_SESSION_NONE) { // Verifica si la sesión no ha sido iniciada
    session_start(); // inicia la sesion para guardar la info del user verificado.
}

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) { // Verifica si el user no esta verificado.
    header("Location: ../index.html"); // redirige al index si no esta verificado.
    exit(); 
}
?>
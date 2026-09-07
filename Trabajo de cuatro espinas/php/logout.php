<?php
session_start();

// mata variables dela secion
$_SESSION = array();

// mata a la secion
session_destroy();

// te manda al index
header("Location: ../index.html");
exit();
?>
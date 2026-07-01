<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'datos';
 $conexion = mysqli_connect($host, $user, $pass, $db);
 
 if (!$conexion){
    die ("Error al conectar".mysqli_connect_error()); 
    }
?>
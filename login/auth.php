<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD']=='POST'){

    $u = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $p = mysqli_real_escape_string($conexion, $_POST['clave']);
    
    $query = "SELECT usuario, rol FROM usuarios WHERE usuario = '$u' AND pass = '$p'";
    
    $resultado = mysqli_query($conexion, $query);

    // Realizar la comparación por fila
    if($fila = mysqli_fetch_assoc($resultado)){
        $_SESSION['usuarios'] = $fila['usuario']; // Corrección: cambiado de 'usuario' a 'usuarios' para mantener compatibilidad
        $_SESSION['rol'] = $fila['rol'];
        header("Location: panel.php");
        exit(); 
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
?>
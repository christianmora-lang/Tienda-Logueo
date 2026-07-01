<?php
session_start(); 

if (isset($_SESSION['usuarios'])) {
    header("Location: panel.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['usuarios'];
    $password = $_POST['clave'];
    
    $res = mysqli_query($conexion, "Select * from usuarios where usuario = '$user' and pass = '$password'");
    $dato = mysqli_fetch_assoc($res);
    
    if ($dato) {
        $_SESSION['usuarios'] = $dato['usuario'];
        $_SESSION['rol'] = $dato['rol'];
        header("Location: panel.php");
        exit();
    } else {
        echo "<script> alert('Acceso denegado');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.onkeydown = function(e) {
            if (e.keyCode == 123) return false; 
            if (e.ctrlKey && e.shiftKey && (e.keyCode == 69 || e.keyCode == 73 || e.keyCode == 74)) return false; 
            if (e.ctrlKey && e.keyCode == 85) return false; 
        }

        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (typeof window.performance != 'undefined' && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</head>
<body>
    <form method="POST">
        <input type="text" name="usuarios" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Clave" required>
        <button type="submit">Ingresar</button>
    </form>

    <br>
    <a href="tienda.php?invitado=1">Ir directo a la Tienda (Invitado)</a>
</body>
</html>
<?php
session_start();//reencuentra la sesion abierta
session_unset();//limpia las sesiones usuario y rol
session_destroy();//elimina la sesion
header("Location: login.php");//redirecciona a login.php
exit();//cierra todo proceso
?>
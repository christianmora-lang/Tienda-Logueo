<?php
session_start();

if (!isset($_SESSION['usuarios'])){
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PANEL DE CONTROL</title>
    
    <script>
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.go(1);
        };
    </script>
</head>
<body>
    <h1>PANEL DE CONTROL</h1>
    <header>
        <h1>BIENVENIDO, <?php echo htmlspecialchars($_SESSION['usuarios']); ?></h1>
        <p>TU ROL ES: <?php echo htmlspecialchars($_SESSION['rol']);?></p>
        <a href="logout.php">cerrar sesion</a> | 
        <a href="tienda.php">Tienda Online</a>
        <?php if (in_array($rol, ['Trabajador', 'admin', 'Gerente'])): ?>
            | <a href="agregar_producto.php">Gestión de Productos</a>
        <?php endif; ?>
    </header>
    <main>
        <?php if ($_SESSION['rol'] == 'Gerente'): ?>
            <section id="panel-Gerente" style="border: 1px solid gold; padding: 20px;">
                <h2>PANEL EXCLUSIVO PARA: GERENTE</h2>
                <a href="reporte_ventas.php" style="color: blue; font-weight: bold;">VER REPORTE DE VENTAS</a>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
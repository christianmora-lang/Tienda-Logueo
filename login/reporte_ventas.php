<?php
session_start();
require 'db.php';

// Seguridad: Solo el Gerente puede entrar
if (!isset($_SESSION['usuarios']) || $_SESSION['rol'] !== 'Gerente') {
    die("Acceso denegado. Solo el Gerente puede ver este reporte.");
}

$query = "SELECT * FROM ventas ORDER BY fecha DESC";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
</head>
<body>
    <h1>REPORTE DE VENTAS TOTALES</h1>
    <a href="panel.php">Volver al Panel</a>
    <hr>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['producto']); ?></td>
                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
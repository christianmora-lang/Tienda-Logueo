<?php
session_start();
require 'db.php';

// Verificar que haya sesión activa y carrito con productos
if (!isset($_SESSION['usuarios']) || empty($_SESSION['carrito'])) {
    header("Location: tienda.php");
    exit();
}

$usuario = $_SESSION['usuarios']; // Corregido: era $_SESSION['usuario'] en el original
$total   = 0;

// Insertar cada item del carrito en la base de datos
foreach ($_SESSION['carrito'] as $item) {
    $nombre_pro = mysqli_real_escape_string($conexion, $item['nombre']);
    $precio_pro = floatval($item['precio']);
    $total     += $precio_pro;

    $query = "INSERT INTO ventas (usuario, producto, precio) VALUES ('$usuario', '$nombre_pro', '$precio_pro')";
    mysqli_query($conexion, $query);
}

// Vaciar el carrito tras la compra
$_SESSION['carrito'] = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Exitosa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif; background: #f4f6f9;
            display: flex; justify-content: center;
            align-items: center; min-height: 100vh;
        }
        .card {
            background: white; border-radius: 12px;
            padding: 40px 35px; text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            max-width: 420px; width: 100%;
        }
        .check { font-size: 4rem; }
        h2 { color: #27ae60; margin: 14px 0 8px; }
        p  { color: #666; margin-bottom: 6px; }
        .total {
            background: #1a1a2e; color: white;
            border-radius: 8px; padding: 14px;
            margin: 20px 0; font-size: 1.4rem; font-weight: bold;
        }
        .total span { font-size: 0.85rem; display: block; opacity: 0.7; margin-bottom: 4px; }
        .btn {
            display: inline-block; padding: 12px 28px;
            background: #27ae60; color: white; border-radius: 8px;
            text-decoration: none; font-size: 1rem; margin-top: 10px;
        }
        .btn:hover { background: #229954; }
    </style>
</head>
<body>
    <div class="card">
        <div class="check">✅</div>
        <h2>¡Compra realizada con éxito!</h2>
        <p>Gracias por tu preferencia, <strong><?php echo htmlspecialchars($usuario); ?></strong>.</p>
        <div class="total">
            <span>TOTAL PAGADO</span>
            $<?php echo number_format($total, 2); ?>
        </div>
        <a href="tienda.php" class="btn">🛒 Seguir comprando</a>
    </div>
</body>
</html>

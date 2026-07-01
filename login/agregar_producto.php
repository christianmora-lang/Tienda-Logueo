<?php
session_start();
require 'db.php';

// ── SEGURIDAD: Solo Trabajador, admin y Gerente pueden entrar ──────────────────
if (!isset($_SESSION['usuarios']) || !in_array($_SESSION['rol'], ['Trabajador', 'admin', 'Gerente'])) {
    header("Location: panel.php");
    exit();
}

$mensaje = '';
$tipoMsg = '';

// ── PROCESAR FORMULARIO ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {

    if ($_POST['accion'] === 'agregar') {
        $nombre = trim(mysqli_real_escape_string($conexion, $_POST['nombre']));
        $precio = floatval($_POST['precio']);

        if ($nombre === '' || $precio <= 0) {
            $mensaje = '⚠️ Por favor ingresa un nombre válido y un precio mayor a 0.';
            $tipoMsg = 'error';
        } else {
            $query = "INSERT INTO productos (nombre, precio) VALUES ('$nombre', '$precio')";
            if (mysqli_query($conexion, $query)) {
                $mensaje = '✅ Producto "' . htmlspecialchars($nombre) . '" agregado correctamente.';
                $tipoMsg = 'ok';
            } else {
                $mensaje = '❌ Error al guardar el producto: ' . mysqli_error($conexion);
                $tipoMsg = 'error';
            }
        }
    }

    if ($_POST['accion'] === 'eliminar' && isset($_POST['id_producto'])) {
        $id = (int) $_POST['id_producto'];
        if (mysqli_query($conexion, "DELETE FROM productos WHERE id = $id")) {
            $mensaje = '🗑 Producto eliminado correctamente.';
            $tipoMsg = 'ok';
        } else {
            $mensaje = '❌ Error al eliminar: ' . mysqli_error($conexion);
            $tipoMsg = 'error';
        }
    }
}

// ── CARGAR PRODUCTOS ──────────────────────────────────────────────────────────
$productos = mysqli_query($conexion, "SELECT * FROM productos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; color: #333; }
        header {
            background: #1a1a2e; color: white;
            padding: 16px 25px; display: flex;
            align-items: center; justify-content: space-between;
        }
        header h1 { font-size: 1.2rem; }
        header a  { color: #aad4f5; text-decoration: none; font-size: 0.9rem; margin-left: 16px; }
        header a:hover { text-decoration: underline; }

        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }

        .card {
            background: white; border-radius: 10px;
            padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;
        }
        .card h2 { color: #2980b9; margin-bottom: 18px; font-size: 1.1rem; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9rem; }
        .form-group input {
            width: 100%; padding: 10px 12px;
            border: 1px solid #ccc; border-radius: 7px; font-size: 1rem;
        }
        .form-group input:focus { outline: none; border-color: #2980b9; }

        .btn-submit {
            background: #2980b9; color: white; border: none;
            padding: 11px 28px; border-radius: 8px; cursor: pointer; font-size: 1rem;
        }
        .btn-submit:hover { background: #2471a3; }
        .btn-del {
            background: #e74c3c; color: white; border: none;
            padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;
        }
        .btn-del:hover { background: #c0392b; }

        .msg { padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; font-weight: bold; }
        .msg.ok    { background: #d5f5e3; color: #1e8449; border: 1px solid #a9dfbf; }
        .msg.error { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #2980b9; color: white; padding: 11px 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f0f4ff; }

        .precio-col { font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>

<header>
    <h1>📦 Gestión de Productos</h1>
    <nav>
        <a href="panel.php">← Panel</a>
        <a href="tienda.php">🛒 Tienda</a>
        <a href="logout.php">🔴 Salir</a>
    </nav>
</header>

<div class="container">

    <?php if ($mensaje): ?>
        <div class="msg <?php echo $tipoMsg; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <!-- FORMULARIO AGREGAR PRODUCTO -->
    <div class="card">
        <h2>➕ Agregar Nuevo Producto</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="agregar">
            <div class="form-group">
                <label for="nombre">Nombre del producto</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Camiseta Azul" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio ($)</label>
                <input type="number" id="precio" name="precio" placeholder="Ej: 25.99"
                       step="0.01" min="0.01" required>
            </div>
            <button type="submit" class="btn-submit">Guardar Producto</button>
        </form>
    </div>

    <!-- LISTADO DE PRODUCTOS -->
    <div class="card">
        <h2>📋 Productos Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'Gerente'): ?>
                    <th>Acción</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = mysqli_fetch_assoc($productos)): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td class="precio-col">$<?php echo number_format($p['precio'], 2); ?></td>
                        <?php if ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'Gerente'): ?>
                        <td>
                            <form method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                                <input type="hidden" name="accion"      value="eliminar">
                                <input type="hidden" name="id_producto" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="btn-del">🗑 Eliminar</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

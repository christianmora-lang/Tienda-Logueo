<?php
session_start();
require 'db.php';

// Si no está logueado pero viene desde el enlace de invitado, le permitimos pasar
if (!isset($_SESSION['usuarios'])){ 
    if (isset($_GET['invitado'])) {
        $_SESSION['usuarios'] = 'Invitado';
        $_SESSION['rol'] = 'cliente';
    } else {
        header("Location: login.php");
        exit();
    }
}

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

if(isset($_POST['id_pro'])){
    $id = $_POST['id_pro'];
    $res = mysqli_query($conexion, "Select * from productos where id= $id ");
    $P = mysqli_fetch_assoc($res);

    if ($P) {
        $_SESSION['carrito'][] = $P;
    }
}

// LÓGICA: Calcular el costo total sumando cada ítem del carrito
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title> TiendaOnline </title>
</head>
<body>
    <header>
        <h1> Tienda Virtual </h1>
        <a href="panel.php"> Volver a panel </a>
    </header>

    <section>
        <h2> Catalogo de Productos </h2>
        <table border ="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Nombre Producto </th>
                    <th>Precio </th>
                    <th>Accion </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = mysqli_query($conexion, "Select * from productos");
                while($row = mysqli_fetch_assoc($res)){
                ?>
                    <tr>
                        <td><?php echo $row['nombre']; ?></td>
                        <td>$<?php echo $row['precio']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id_pro" value="<?php echo $row['id']; ?>">
                                <button type="submit">Agregar al carrito</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <aside style="margin-top: 20px; background: #f0f0f0; padding: 15px; border: 1px solid #ccc; width: 300px;">
        <h3>Carrito de Compras (<?php echo count($_SESSION['carrito']); ?> items)</h3>
        <ul>
            <?php 
            if(!empty($_SESSION['carrito'])):
                foreach($_SESSION['carrito'] as $item): 
            ?>
                    <li><?php echo $item['nombre']; ?> - $<?php echo $item['precio']; ?></li>
            <?php 
                endforeach; 
            else:
                echo "<li>El carrito está vacío</li>";
            endif; 
            ?>
        </ul>
        
        <?php if(!empty($_SESSION['carrito'])): ?>
            <p><strong>TOTAL A PAGAR: $<?php echo number_format($total, 2); ?></strong></p>
        <?php endif; ?>

        <hr>
        <a href="logout.php">Vaciar Sesión y Salir</a>
        
        <?php if(!empty($_SESSION['carrito'])): ?>
            <br><br>
            <a href="checkout.php" style="display:block; padding:5px; background:green; color:white; text-align:center; text-decoration:none;">FINALIZAR COMPRA</a>
        <?php endif; ?>
    </aside>

    <footer>
        <p><small>Arquitectura Applied: Cliente-Servidor con Inyección de Datos Dinámicos.</small></p>
    </footer>
</body>
</html>
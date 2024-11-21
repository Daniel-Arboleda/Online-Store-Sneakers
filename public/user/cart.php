<!-- cart.php -->


<?php
require __DIR__ . '/cart_logic.php';

// Asegúrate de que $productos_carrito esté definido
if (!isset($productos_carrito)) {
    $productos_carrito = [];
}

// Asigna $productos_carrito a $productos para el formulario
$productos = $productos_carrito;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
   
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <h2>Tu Carrito de Compras</h2>
        <!-- Tabla para mostrar los productos en el carrito -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos_carrito) > 0): ?>
                    <?php foreach ($productos_carrito as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['item_id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                            <td>
                                <input type="number" class="cantidad-producto form-control" name="cantidad" id="cantidad" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" min="1" max="99" readonly>
                            </td>
                            <td>
                                <?php if ($producto['imagen'] && file_exists(__DIR__ . '/../../uploads/' . $producto['imagen'])): ?>
                                    <img src="../../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen" style="max-width: 100px; height: auto;">
                                <?php else: ?>
                                    <img src="../../uploads/Nike_1985_cuadrado_rojo.webp" alt="">
                                    No disponible
                                <?php endif; ?>   
                            </td>

                            <td>
                                <!-- Formulario para eliminar un producto del carrito -->
                                <form action="eliminar_item.php" method="post" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($producto['item_id']); ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Formulario de código de descuento -->
        <form action="" method="post">
            <div class="form-group">
                <label for="codigo_descuento">Código de Descuento</label>
                <input type="text" id="codigo_descuento" name="codigo_descuento" class="form-control" placeholder="Introduce tu código de descuento">
                <button type="submit" class="btn btn-primary mt-2">Aplicar Descuento</button>
            </div>
        </form>

        <!-- Mostrar el total del carrito -->
        <h4>Total: $<?php echo number_format($total_carrito, 2); ?></h4>

        <!-- Mostrar el descuento y el total con descuento si se aplicó -->
        <?php if (isset($total_con_descuento)): ?>
            <div class="alert alert-success mt-3">
                ¡Descuento Aplicado! Has ahorrado: $<?php echo number_format($descuento, 2); ?>
                <br>
                Total después del descuento: $<?php echo number_format($total_con_descuento, 2); ?>
            </div>
        <?php endif; ?>

        
        <!-- Botón para pagar -->
        <button onclick="abrirFactura()">Proceder al Pago</button>

        <script>
        function abrirFactura() {
            window.open('ver_factura.php', 'Factura', 'width=800,height=600,scrollbars=yes');
        }
        </script>

        <!-- Botón para pagar -->
        <!-- <form action="pagar.php" method="post">
            <button type="submit" class="btn btn-success mt-3">Proceder al Pago</button>
        </form> -->



        


      



        <!-- Botón Redirigir a la Tienda-->
        <a href="tienda.php" class="btn btn-secondary btn-block mt-2">
            <i class="fas fa-store"></i> Ir a la Tienda
        </a>
</div>
</body>
</html>
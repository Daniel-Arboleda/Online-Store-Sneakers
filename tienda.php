<!-- tienda.php -->
<?php
// Incluir la conexión a la base de datos
require 'conexion.php';

// Obtener los productos de la tabla "productos"
$sql = "SELECT id, nombre, descripcion, precio, cantidad, imagen FROM productos";
$resultado = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - StoreThays</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"> <!-- Bootstrap Icons -->
</head>
<body>
    <!-- Menú de navegación -->
    <?php include 'menu_out.php'; ?>
    <h1 class="text-center mt-4">Tienda</h1>
    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Productos Disponibles</h2>
        <div class="row">
            <?php
            // Verificar si hay productos disponibles
            if ($resultado->num_rows > 0) {
                // Recorrer cada producto y mostrarlo en una tarjeta
                while ($producto = $resultado->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" onerror="this.onerror=null; this.src='/TiendaTenis/uploads/Nike_1985_cuadrado_rojo.webp';">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <p class="card-text font-weight-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                                <p class="card-text text-muted">Stock: <?php echo htmlspecialchars($producto['cantidad']); ?> unidades</p>

                                <!-- Selector de Cantidad -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1, '<?php echo $producto['id']; ?>')">-</button>
                                    </div>
                                    <input type="text" class="form-control text-center" id="quantity-<?php echo $producto['id']; ?>" value="1" min="1" max="<?php echo htmlspecialchars($producto['cantidad']); ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1, '<?php echo $producto['id']; ?>', <?php echo htmlspecialchars($producto['cantidad']); ?>)">+</button>
                                    </div>
                                </div>

                                <!-- Botón Añadir al Carrito -->
                                <a href="agregar_carrito.php?id=<?php echo htmlspecialchars($producto['id']); ?>&cantidad=" id="add-to-cart-<?php echo $producto['id']; ?>" class="btn btn-primary btn-block" onclick="addToCart('<?php echo $producto['id']; ?>')">Añadir al carrito</a>

                                <!-- Botón Redirigir al Carrito -->
                                <a href="cart.php" class="btn btn-secondary btn-block mt-2">
                                    <i class="bi bi-cart3"></i> Ir al Carrito
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No hay productos disponibles.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Cambiar cantidad de producto en el selector
        function changeQuantity(change, productId, stock) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let currentQuantity = parseInt(quantityInput.value) || 1;
            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > stock) currentQuantity = stock; // No exceder el stock
            quantityInput.value = currentQuantity;
        }

        // Agregar al carrito con la cantidad seleccionada
        function addToCart(productId) {
            const quantity = document.getElementById(`quantity-${productId}`).value;
            const addToCartBtn = document.getElementById(`add-to-cart-${productId}`);
            addToCartBtn.href = `agregar_carrito.php?id=${productId}&cantidad=${quantity}`;
        }
    </script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$mysqli->close();
?>

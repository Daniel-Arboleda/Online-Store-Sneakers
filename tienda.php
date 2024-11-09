<!-- tienda.php -->
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
require 'conexion.php';

// Inicializar la consulta base
$sql = "SELECT id, nombre, descripcion, precio, cantidad, imagen FROM productos";

// Verificar si se ha enviado un término de búsqueda
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $sql .= " WHERE nombre LIKE '%$searchTerm%'";
}

// Ejecutar la consulta
$resultado = $mysqli->query($sql);

// Verificar si la consulta se ejecutó correctamente
if (!$resultado) {
    echo "Error en la consulta: " . $mysqli->error;
}

// Reemplazamos la obtención de la puntuación promedio dentro del ciclo de productos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Menú de navegación -->
    <?php include 'menu.php'; ?>
    <!-- <h1 class="text-center mt-4">Tu Tienda</h1> -->
    <h2 class="text-center mt-4">Tu Tienda</h2>
    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Productos Disponibles</h2>
        
        <!-- Filtro de Productos -->
        <div class="mb-4">
            <form method="GET" action="tienda.php">
                <input type="text" name="search" placeholder="Buscar productos..." class="form-control" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary mt-2">Buscar</button>
            </form>
        </div>

        <div class="row">
            <?php
            // Verificar si hay productos disponibles
            if ($resultado->num_rows > 0) {
                // Recorrer cada producto y mostrarlo en una tarjeta
                while ($producto = $resultado->fetch_assoc()) {
                    // Obtener la puntuación promedio para cada producto
                    $sql_avg = "SELECT AVG(puntuacion) as promedio FROM calificaciones WHERE producto_id = ?";
                    $stmt_avg = $mysqli->prepare($sql_avg);
                    $stmt_avg->bind_param('i', $producto['id']);
                    $stmt_avg->execute();
                    $result_avg = $stmt_avg->get_result();
                    $avg_rating = $result_avg->fetch_assoc()['promedio'] ?? 0;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" onerror="this.onerror=null; this.src='/TiendaTenis/uploads/Nike_1985_cuadrado_rojo.webp';">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <p class="card-text font-weight-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                                <p class="card-text text-muted">Stock: <?php echo htmlspecialchars($producto['cantidad']); ?> unidades</p>

                                <!-- Formulario de puntuación -->
                                <div class="rating mb-2">
                                    <label>Calificar:</label>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star" id="star-<?php echo $producto['id'] . '-' . $i; ?>" onclick="rateProduct(<?php echo $producto['id']; ?>, <?php echo $i; ?>)"></i>
                                    <?php endfor; ?>
                                </div>

                                <!-- Puntuación promedio -->
                                <p class="card-text">Puntuación promedio: <?php echo number_format($avg_rating, 1); ?> / 5</p>

                                <!-- Selector de Cantidad -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1, '<?php echo $producto['id']; ?>', <?php echo htmlspecialchars($producto['cantidad']); ?>)">-</button>
                                    </div>
                                    <input type="text" class="form-control text-center" id="quantity-<?php echo $producto['id']; ?>" value="1" min="1" max="<?php echo htmlspecialchars($producto['cantidad']); ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1, '<?php echo $producto['id']; ?>', <?php echo htmlspecialchars($producto['cantidad']); ?>)">+</button>
                                    </div>
                                </div>

                                <!-- Botón Añadir al Carrito -->
                                <a href="javascript:void(0);" id="add-to-cart-<?php echo $producto['id']; ?>" class="btn btn-primary btn-block" onclick="addToCart('<?php echo $producto['id']; ?>')">Añadir al carrito</a>



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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Funciones de interacción y calificación
        function rateProduct(productId, puntuacion) {
            $.ajax({
                url: 'rating.php',
                type: 'POST',
                data: {
                    producto_id: productId,
                    puntuacion: puntuacion,
                    usuario_email: "<?php echo $_SESSION['email']; ?>"
                },
                success: function(response) {
                    alert(response);
                    highlightStars(productId, puntuacion);
                    console.log("Producto ID:", productId, "Puntuación:", puntuacion);
                },
                error: function() {
                    alert("Error al registrar la puntuación. Inténtalo nuevamente.");
                }
            });
        }

        function highlightStars(productId, rating) {
            for (let i = 1; i <= 5; i++) {
                let star = document.getElementById(`star-${productId}-${i}`);
                star.classList.toggle('bi-star-fill', i <= rating);
                star.classList.toggle('bi-star', i > rating);
            }
        }

        function changeQuantity(change, productId, maxQuantity) {
            let quantityInput = document.getElementById(`quantity-${productId}`);
            let currentQuantity = parseInt(quantityInput.value);

            // Aumenta o disminuye la cantidad
            let newQuantity = currentQuantity + change;

            // Asegura que la cantidad esté dentro del rango permitido
            if (newQuantity >= 1 && newQuantity <= maxQuantity) {
                quantityInput.value = newQuantity;
            }
            console.log(newQuantity)
            console.log(currentQuantity)
        }

        // function addToCart(productId) {
        //     let quantityInput = document.getElementById(`quantity-${productId}`);
        //     let selectedQuantity = quantityInput.value;

        //     // Auditoría en la consola para verificar la cantidad seleccionada
        //     console.log("Producto ID:", productId, "Cantidad seleccionada:", selectedQuantity);

        //     // Redirecciona con la cantidad seleccionada
        //     window.location.href = `agregar_al_carrito.php?id=${productId}&cantidad=${selectedQuantity}`;
        // }

        function addToCart(productId) {
            let quantityInput = document.getElementById(`quantity-${productId}`);
            let selectedQuantity = parseInt(quantityInput.value);
            let maxQuantity = parseInt(quantityInput.getAttribute('max'));

            console.log("Producto ID:", productId, "Cantidad seleccionada:", selectedQuantity, "Cantidad máxima posible:", maxQuantity);
            

            // Realizar la solicitud AJAX para agregar al carrito
            $.ajax({
                url: 'agregar_al_carrito.php',
                type: 'GET',
                data: {
                    id: productId,
                    cantidad: selectedQuantity
                },
                success: function(response) {
                    // Si se agrega al carrito correctamente, actualizar la interfaz sin redirigir
                    alert("Producto añadido al carrito");
                    // Aquí podrías actualizar el contador del carrito en la página o la vista del carrito si es necesario.
                },
                error: function() {
                    alert("Hubo un error al añadir el producto al carrito.");
                }
            });
        }

    </script>

</body>
</html>

<?php
$mysqli->close();
?>

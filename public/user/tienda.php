<!-- tienda.php -->
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
require __DIR__ . '/../../config/conexion.php';

// Consultar géneros y categorías para mostrarlos en los filtros
$genero_sql = "SELECT id, nombre FROM generos";
$genero_result = $mysqli->query($genero_sql);

$categoria_sql = "SELECT id, nombre FROM categorias";
$categoria_result = $mysqli->query($categoria_sql);

// Inicializar la consulta base de productos
$sql = "SELECT id, nombre, descripcion, precio, cantidad, imagen FROM productos WHERE 1=1";

// Filtro de búsqueda por nombre
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = htmlspecialchars($_GET['search']);
    $sql .= " AND nombre LIKE '%$searchTerm%'";
}

// Filtro por género
if (isset($_GET['genero']) && !empty($_GET['genero'])) {
    $genero = intval($_GET['genero']);
    $sql .= " AND genero_id = $genero";
}

// Filtro por categoría
if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria = intval($_GET['categoria']);
    $sql .= " AND categoria_id = $categoria";
}

// Ejecutar la consulta de productos
$resultado = $mysqli->query($sql);

if (!$resultado) {
    echo "Error en la consulta: " . $mysqli->error;
}

// Obtener todos los productos de la base de datos, incluyendo el nombre de la categoría y el género
$sql = "SELECT productos.id, productos.nombre, productos.descripcion, productos.precio, productos.cantidad, productos.imagen, 
            categorias.nombre AS categoria_nombre, generos.nombre AS genero_nombre
        FROM productos
        LEFT JOIN categorias ON productos.categoria_id = categorias.id
        LEFT JOIN generos ON productos.genero_id = generos.id";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <!-- Menú de navegación -->
    <?php include 'menu.php'; ?>
    <h2 class="text-center mt-4">Tu Tienda</h2>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Productos Disponibles</h2>

        <!-- Filtro de Productos -->
        <div class="mb-4">
            <form method="GET" action="tienda.php">
                <div class="form-group">
                    <input type="text" name="search" placeholder="Buscar productos..." class="form-control" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                
                <!-- Filtro de Género -->
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select name="genero" class="form-control">
                        <option value="">Todos</option>
                        <?php while ($genero = $genero_result->fetch_assoc()): ?>
                            <option value="<?php echo $genero['id']; ?>" <?php echo (isset($_GET['genero']) && $_GET['genero'] == $genero['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genero['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Filtro de Categoría -->
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" class="form-control">
                        <option value="">Todas</option>
                        <?php while ($categoria = $categoria_result->fetch_assoc()): ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>

        <!-- Productos -->
        <div class="row">
            <?php
            if ($resultado->num_rows > 0) {
                while ($producto = $resultado->fetch_assoc()) {
                    // Consulta para obtener la puntuación promedio de cada producto
                    $sql_avg = "SELECT AVG(puntuacion) as promedio FROM calificaciones WHERE producto_id = ?";
                    $stmt_avg = $mysqli->prepare($sql_avg);
                    $stmt_avg->bind_param('i', $producto['id']);
                    $stmt_avg->execute();
                    $result_avg = $stmt_avg->get_result();
                    $avg_rating = $result_avg->fetch_assoc()['promedio'] ?? 0;
            ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">

                            <!-- <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" onerror="this.onerror=null; this.src='../../uploads/Nike_1985_cuadrado_rojo.webp'; "> -->


                            <?php if ($producto['imagen'] && file_exists(__DIR__ . '/../../uploads/' . $producto['imagen'])): ?>
                                <img src="../../uploads/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen">
                            <?php else: ?>
                                <img src="../../uploads/Nike_1985_cuadrado_rojo.webp" alt="">
                                No disponible
                            <?php endif; ?>                            

                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <p class="card-text font-weight-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                                <p class="card-text text-muted">Stock: <?php echo htmlspecialchars($producto['cantidad']); ?> unidades</p>
                                <p class="card-text">Puntuación promedio: <?php echo number_format($avg_rating, 1); ?> / 5</p>


                                <!-- Contenedor para seleccionar cantidad -->
                                <div class="quantity-selector">
                                    <button type="button" 
                                        class="btn btn-outline-secondary" onclick="changeQuantity(-1, '<?php echo $producto['id']; ?>', <?php echo $producto['cantidad']; ?>)">-</button>
                                    <input type="number" 
                                        id="quantity-<?php echo $producto['id']; ?>" 
                                        value="1" 
                                        min="1" 
                                        max="<?php echo $producto['cantidad']; ?>" 
                                        readonly 
                                        class="form-control d-inline w-auto text-center">
                                    <button type="button" 
                                        class="btn btn-outline-secondary" onclick="changeQuantity(1, '<?php echo $producto['id']; ?>', <?php echo $producto['cantidad']; ?>)">+</button>
                                </div>

                                <a href="javascript:void(0);" id="add-to-cart-<?php echo $producto['id']; ?>" class="btn btn-primary btn-block mt-2" onclick="addToCart('<?php echo $producto['id']; ?>')">Añadir al carrito</a>

                                <a href="cart.php" id="" class="btn btn-primary btn-block mt-2">
                                    <i class="fas fa-shopping-cart"></i> Ir al carrito
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        // Función para añadir al carrito
        function addToCart(productId) {
            let quantityInput = document.getElementById(`quantity-${productId}`);
            let selectedQuantity = parseInt(quantityInput.value);
            let maxQuantity = parseInt(quantityInput.getAttribute('max'));

            $.ajax({
                url: 'agregar_al_carrito.php',
                type: 'GET',
                data: {
                    id: productId,
                    cantidad: selectedQuantity
                },
                success: function(response) {
                    alert("Producto añadido al carrito");
                },
                error: function() {
                    alert("Hubo un error al añadir el producto al carrito.");
                }
            });
        }
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script src="../assets/js/tienda.js"></script>

</body>
</html>

<?php
$mysqli->close();
?>

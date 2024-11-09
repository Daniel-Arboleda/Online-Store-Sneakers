<?php
session_start();
// require 'conexion.php';
require __DIR__ . '../config/conexion.php';


// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id']; // Suponiendo que tienes un ID de usuario en la sesión

// Consultar los productos en el carrito
$sql = "SELECT producto_id, nombre, descripcion, precio, cantidad, imagen FROM carrito WHERE usuario_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}
$mysqli->close();  // Cerrar la conexión

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h2>Carrito de Compras</h2>

        <!-- Tarjetas para mostrar los productos en el carrito -->
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" class="card-img-top" alt="Imagen del producto">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                <p class="card-text"><strong>Precio:</strong> $<?php echo htmlspecialchars($row['precio']); ?></p>
                                <p class="card-text"><strong>Cantidad:</strong> <?php echo htmlspecialchars($row['cantidad']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay productos en el carrito.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

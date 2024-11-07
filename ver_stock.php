<!-- ver_stock.php -->

<?php
require 'conexion.php';
session_start();
// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
// Consultar los productos
$sql = "SELECT id, nombre, descripcion, precio, cantidad, imagen FROM productos";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock de Productos - StoreThays</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <h2>Stock de Productos</h2>
        <!-- Tabla para mostrar el stock de productos -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($row['precio']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                            <td>
                                <?php if ($row['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen del producto" style="max-width: 100px;">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="agregar_al_carrito.php" method="post">
                                    <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-success">Comprar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay productos en stock.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

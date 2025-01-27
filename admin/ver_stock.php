<!-- ver_stock.php -->

<?php
session_start();
require __DIR__ . '/../config/conexion.php';

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Consultar los productos
$sql = "SELECT id, nombre, descripcion, precio, cantidad, imagen FROM productos";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-dark text-warning">
    <?php include 'menu_admin.php'; ?>

    <div class="container mt-5">
        <h2>Stock de Productos</h2>
        <table class="table table-bordered table-striped text-warning">
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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($row['precio']); ?></td>
                            <td><?php echo htmlspecialchars($row['cantidad']); ?></td>

                            <td>
                                <?php
                                if ($row['imagen'] && file_exists(__DIR__ . '/../uploads/' . $row['imagen'])) {
                                    echo '<img src="../uploads/' . htmlspecialchars($row['imagen']) . '" alt="Imagen" style="max-width: 100px;">';
                                } else {
                                    // echo 'No disponible';
                                    echo '<img src="../uploads/Nike_1985_cuadrado_rojo.webp" alt="Imagen predeterminada" style="max-width: 100px;">';

                                }
                                ?>
                            </td>



                            <td>
                                <form action="eliminar_producto.php" method="post">
                                    <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger">Eliminar</button>
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
</body>
</html>

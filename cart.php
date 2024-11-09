<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html'); // Redirigir al formulario de login si no está autenticado
    exit();
}

// Incluir el archivo de conexión a la base de datos
require 'conexion.php';

// Obtener el ID del usuario de la sesión
$usuario_id = $_SESSION['user_id']; // Asegúrate de usar el ID de usuario correcto aquí

// Consultar los productos en el carrito del usuario
$sql = "
    SELECT ci.id AS item_id, p.nombre, p.descripcion, p.precio, ci.cantidad, p.imagen
    FROM carrito c
    INNER JOIN carrito_item ci ON c.id = ci.carrito_id
    INNER JOIN productos p ON ci.producto_id = p.id
    WHERE c.usuario_id = ?
";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
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
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <h2>Carrito de Compras</h2>
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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_id']); ?></td>
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
                                <!-- Formulario para eliminar un producto del carrito -->
                                <form action="eliminar_item.php" method="post" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($row['item_id']); ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
        <!-- Botón para pagar -->
        <form action="pagar.php" method="post">
            <button type="submit" class="btn btn-success">Pagar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

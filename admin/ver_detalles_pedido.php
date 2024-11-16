<!-- ver_detalles_pedido.php -->



<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Incluir la conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';

$id_pedido = $_GET['id'];

// Obtener detalles del pedido
$sql_pedido = "SELECT p.id, p.usuario_id, p.fecha, p.estado, p.total, u.nombre, u.apellido, u.direccion_envio, u.metodo_pago
               FROM pedidos p
               JOIN usuarios u ON p.usuario_id = u.id
               WHERE p.id = ?";
$stmt = $mysqli->prepare($sql_pedido);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

// Obtener productos en el pedido
$sql_productos = "SELECT dp.producto_id, pr.nombre, dp.cantidad, dp.precio
                  FROM detalles_pedido dp
                  JOIN productos pr ON dp.producto_id = pr.id
                  WHERE dp.pedido_id = ?";
$stmt_productos = $mysqli->prepare($sql_productos);
$stmt_productos->bind_param("i", $id_pedido);
$stmt_productos->execute();
$productos = $stmt_productos->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_admin.php'; ?>

    <div class="container mt-5">
        <h2>Detalles del Pedido <?php echo htmlspecialchars($pedido['id']); ?></h2>

        <h4>Cliente: <?php echo htmlspecialchars($pedido['nombre']) . ' ' . htmlspecialchars($pedido['apellido']); ?></h4>
        <p>Fecha: <?php echo htmlspecialchars($pedido['fecha']); ?></p>
        <p>Estado: <?php echo htmlspecialchars($pedido['estado']); ?></p>
        <p>Dirección de Envío: <?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>
        <p>Método de Pago: <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
        <p>Total: $<?php echo number_format($pedido['total'], 2); ?></p>

        <h4>Productos en el Pedido</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>$<?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin_pedidos.php" class="btn btn-secondary">Volver a Pedidos Pendientes</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/../../config/conexion.php';

if (!isset($_GET['pedido_id']) || empty($_GET['pedido_id'])) {
    echo "No se especificó un pedido válido.";
    exit();
}

$pedido_id = $_GET['pedido_id'];
$usuario_id = $_SESSION['user_id'];

// Validar que el pedido pertenece al usuario logueado
$query_check = "SELECT * FROM pedidos WHERE id = ? AND usuario_id = ?";
$stmt_check = $mysqli->prepare($query_check);
$stmt_check->bind_param('ii', $pedido_id, $usuario_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "No tienes permisos para ver este pedido o el pedido no existe.";
    exit();
}

// Obtener los detalles del pedido
$query = "SELECT * FROM detalle_pedido dp 
          JOIN productos p ON dp.producto_id = p.id 
          WHERE dp.pedido_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Detalle del Pedido #<?php echo htmlspecialchars($pedido_id); ?></h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                        <td><?php echo $detalle['cantidad']; ?></td>
                        <td>$<?php echo number_format($detalle['precio'], 2); ?></td>
                        <td>$<?php echo number_format($detalle['cantidad'] * $detalle['precio'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="mis_pedidos.php" class="btn btn-primary">Volver a Mis Pedidos</a>
    </div>
</body>
</html>

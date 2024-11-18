<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id']) || !isset($_GET['pedido_id'])) {
    echo "Error: Datos faltantes.";
    exit();
}

// Incluir el archivo de conexión y modelo
require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../includes/modelo_pedidos.php';

$pedido_id = $_GET['pedido_id'];
$pedido = obtenerDetallePedido($pedido_id, $_SESSION['user_id']);

if (!$pedido) {
    echo "No se encontró el pedido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Pedido</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Detalle del Pedido #<?php echo $pedido['id']; ?></h1>
        <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d-m-Y", strtotime($pedido['fecha'])); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
        <p><strong>Dirección de envío:</strong> <?php echo htmlspecialchars($pedido['direccion_envio']); ?></p>

        <!-- Aquí agregar la lista de productos del pedido -->
        <h3>Productos</h3>
        <ul>
            <?php foreach ($pedido['productos'] as $producto): ?>
                <li><?php echo $producto['nombre']; ?> (x<?php echo $producto['cantidad']; ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

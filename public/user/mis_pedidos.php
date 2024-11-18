<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "Error: No se encuentra el ID del usuario en la sesión.";
    exit();
}

// Incluir el archivo de conexión y modelo
require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/modelo_pedidos.php';

// Consultar los pedidos del usuario
$usuario_id = $_SESSION['user_id'];
$pedidos = obtenerPedidosPorUsuario($usuario_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Incluir el menú -->
    <div class="container">
        <h1>Mis Pedidos</h1>
        
        <?php if ($pedidos && count($pedidos) > 0): ?>
            <div class="list-group">
                <?php foreach ($pedidos as $pedido): ?>
                    <a href="detalle_pedido.php?pedido_id=<?php echo $pedido['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pedido #<?php echo $pedido['id']; ?></h5>
                            <small><?php echo date("d-m-Y", strtotime($pedido['fecha'])); ?></small>
                        </div>
                        <p class="mb-1">Total: $<?php echo number_format($pedido['total'], 2); ?></p>
                        <small>Estado: <?php echo htmlspecialchars($pedido['estado']); ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No tienes pedidos aún.</p>
        <?php endif; ?>
    </div>
</body>
</html>

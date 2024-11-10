<?php
session_start();

// Verificar si el usuario está autenticado
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header('Location: login.php');
//     exit();
// }

// Verificar si el ID de usuario está en la sesión
if (!isset($_SESSION['user_id'])) {
    echo "Error: No se encuentra el ID del usuario en la sesión.";
    exit();
}

// Imprimir los datos del usuario para verificación
// echo "usuario_id en la sesión: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'No encontrado');
// echo " usuario_id en la sesión: " . (isset($_SESSION['email']) ? $_SESSION['email'] : 'No encontrado');

// Incluir el archivo de conexión
require '../config/conexion.php';

// Consultar los pedidos del usuario actual
$usuario_id = $_SESSION['user_id'];
$query = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $mysqli->prepare($query); // Reemplazamos $conn con $mysqli

if ($stmt) {
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error en la consulta: " . $mysqli->error;
    exit();
}
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
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="list-group">
                <?php while ($pedido = $result->fetch_assoc()): ?>
                    <a href="detalle_pedido.php?pedido_id=<?php echo $pedido['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pedido #<?php echo $pedido['id']; ?></h5>
                            <small><?php echo date("d-m-Y", strtotime($pedido['fecha'])); ?></small>
                        </div>
                        <p class="mb-1">Total: $<?php echo number_format($pedido['total'], 2); ?></p>
                        <small>Estado: <?php echo htmlspecialchars($pedido['estado']); ?></small>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No tienes pedidos aún.</p>
        <?php endif; ?>
    </div>
</body>
</html>

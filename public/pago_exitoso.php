<!-- pago_exitoso.php -->

<?php
session_start();
require 'conexion.php';
require 'paypal_config.php';

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

if (!isset($_GET['orderID'])) {
    header('Location: cart.php');
    exit();
}

$orderID = $_GET['orderID'];

// Aquí puedes realizar una consulta a la API de PayPal para verificar el estado del pedido
// También puedes registrar el pedido en la base de datos y limpiar el carrito del usuario

// Registrar el pedido en la base de datos (ejemplo básico)
$total = 0; // Reemplaza con el total calculado del carrito

// Crear el pedido en la base de datos
$stmt = $conn->prepare("INSERT INTO pedidos (user_id, total, estado, fecha) VALUES (?, ?, 'pagado', NOW())");
$stmt->bind_param("id", $_SESSION['user_id'], $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;

// Agregar detalles del pedido
foreach ($_SESSION['carrito'] as $item) {
    $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmt_detalle->bind_param("iiid", $pedido_id, $item['producto_id'], $item['cantidad'], $item['precio']);
    $stmt_detalle->execute();
}

// Limpiar el carrito
unset($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Exitoso</title>
</head>
<body>
    <h1>Pago Exitoso</h1>
    <p>Tu pago ha sido procesado exitosamente. Número de pedido: <?php echo htmlspecialchars($pedido_id); ?></p>
    <a href="mis_pedidos.php">Ver mis pedidos</a>
</body>
</html>

<?php

// `actualizar_cantidad.php`
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';



$producto_id = $_POST['producto_id'];
$cantidad_nueva = $_POST['cantidad'];
$usuario_id = $_SESSION['user_id'];

// Obtener el stock del producto
$sql_stock = "SELECT stock FROM productos WHERE id = ?";
$stmt_stock = $mysqli->prepare($sql_stock);
$stmt_stock->bind_param("i", $producto_id);
$stmt_stock->execute();
$result_stock = $stmt_stock->get_result();
$producto = $result_stock->fetch_assoc();

if ($producto) {
    $stock_disponible = $producto['stock'];

    // Obtener la cantidad actual del producto en el carrito
    $sql_carrito = "
        SELECT cantidad FROM carrito_item 
        WHERE producto_id = ? AND carrito_id = (SELECT id FROM carrito WHERE usuario_id = ?)
    ";
    $stmt_carrito = $mysqli->prepare($sql_carrito);
    $stmt_carrito->bind_param("ii", $producto_id, $usuario_id);
    $stmt_carrito->execute();
    $result_carrito = $stmt_carrito->get_result();
    $carrito_item = $result_carrito->fetch_assoc();

    $cantidad_actual_en_carrito = $carrito_item ? $carrito_item['cantidad'] : 0;
    $cantidad_total = $cantidad_nueva;

    // Verificar si la cantidad total deseada excede el stock
    if ($cantidad_total > $stock_disponible) {
        die("No puedes exceder la cantidad en stock.");
    }

    // Actualizar la cantidad en el carrito
    $sql_actualizar = "
        UPDATE carrito_item
        SET cantidad = ?
        WHERE producto_id = ? AND carrito_id = (SELECT id FROM carrito WHERE usuario_id = ?)
    ";
    $stmt_actualizar = $mysqli->prepare($sql_actualizar);
    $stmt_actualizar->bind_param("iii", $cantidad_nueva, $producto_id, $usuario_id);
    $stmt_actualizar->execute();
    $stmt_actualizar->close();
} else {
    die("Producto no encontrado.");
}

$stmt_stock->close();
$stmt_carrito->close();
$mysqli->close();
header("Location: cart.php");
?>

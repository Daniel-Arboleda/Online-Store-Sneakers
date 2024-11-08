<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Incluir la conexión a la base de datos
require 'conexion.php';

// Obtener los datos del producto y la cantidad seleccionada
$productId = isset($_GET['id']) ? $_GET['id'] : null;
$quantity = isset($_GET['cantidad']) ? $_GET['cantidad'] : 1;

// Verificar que los datos son válidos
if (!$productId || !is_numeric($quantity) || $quantity <= 0) {
    header('Location: tienda.php');
    exit();
}

// Consultar la cantidad disponible en stock para el producto
$sql = "SELECT cantidad FROM productos WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
    $stockDisponible = $producto['cantidad'];

    // Verificar si la cantidad seleccionada excede el stock
    if ($quantity > $stockDisponible) {
        echo "<script>alert('No puedes agregar más de lo que hay en el stock disponible.');</script>";
        header('Location: tienda.php');
        exit();
    }

    // Verificar si el producto ya está en el carrito
    $userEmail = $_SESSION['email'];
    $sqlCarrito = "SELECT * FROM carrito WHERE usuario_email = ? AND producto_id = ?";
    $stmtCarrito = $mysqli->prepare($sqlCarrito);
    $stmtCarrito->bind_param('si', $userEmail, $productId);
    $stmtCarrito->execute();
    $resultadoCarrito = $stmtCarrito->get_result();

    if ($resultadoCarrito->num_rows > 0) {
        // Si el producto ya está en el carrito, solo actualizar la cantidad
        $carrito = $resultadoCarrito->fetch_assoc();
        $nuevaCantidad = $carrito['cantidad'] + $quantity;

        // Verificar que la nueva cantidad no exceda el stock
        if ($nuevaCantidad > $stockDisponible) {
            echo "<script>alert('No puedes agregar más productos de los que hay en el stock disponible.');</script>";
            header('Location: tienda.php');
            exit();
        }

        // Actualizar el carrito con la nueva cantidad
        $sqlUpdate = "UPDATE carrito SET cantidad = ? WHERE id = ?";
        $stmtUpdate = $mysqli->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ii', $nuevaCantidad, $carrito['id']);
        $stmtUpdate->execute();
    } else {
        // Si el producto no está en el carrito, agregarlo
        $sqlInsert = "INSERT INTO carrito (usuario_email, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmtInsert = $mysqli->prepare($sqlInsert);
        $stmtInsert->bind_param('sii', $userEmail, $productId, $quantity);
        $stmtInsert->execute();
    }

    // Redirigir a la tienda o carrito después de añadir el producto
    header('Location: tienda.php');
} else {
    echo "<script>alert('El producto no existe o ha sido eliminado.');</script>";
    header('Location: tienda.php');
}

$mysqli->close();
?>

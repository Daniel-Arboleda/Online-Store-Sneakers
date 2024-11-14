<!-- eliminar_item.php -->

<?php
session_start(); // Iniciar la sesión
require __DIR__ . '/../config/conexion.php';


// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html');
    exit();
}

// Verificar si se recibió el ID del item
if (!isset($_POST['item_id'])) {
    die("ID de producto no proporcionado.");
}

// Incluir el archivo de conexión a la base de datos
require 'conexion.php';

// Obtener el ID del item a eliminar
$item_id = $_POST['item_id'];

// Preparar y ejecutar la consulta SQL para eliminar el item del carrito
$sql = "DELETE FROM carrito_item WHERE id = ?";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $item_id);
    if ($stmt->execute()) {
        header("Location: cart.php"); // Redirigir de nuevo al carrito después de eliminar el item
        exit();
    } else {
        die("Error al eliminar el producto del carrito: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

$mysqli->close();
?>

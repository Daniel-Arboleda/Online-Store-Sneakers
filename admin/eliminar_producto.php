<?php
session_start();
require __DIR__ . '/../config/conexion.php';

// Verificar que el usuario es administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['producto_id'])) {
    $producto_id = $_POST['producto_id'];

    // Eliminar producto de la base de datos
    $stmt = $mysqli->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $stmt->close();
}
header('Location: stock_productos.php');
exit();
?>

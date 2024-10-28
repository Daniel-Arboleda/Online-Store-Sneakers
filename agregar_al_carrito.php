<?php
require 'conexion.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html');
    exit();
}

// Verificar si el ID de usuario está en la sesión
if (!isset($_SESSION['user_id'])) {
    die("ID de usuario no disponible en la sesión.");
}

$usuario_id = $_SESSION['user_id']; // ID de usuario desde la sesión

// Verificar si se ha enviado un ID de producto
if (isset($_POST['producto_id'])) {
    $producto_id = intval($_POST['producto_id']);

    // Consultar el producto
    $sql = "SELECT nombre, descripcion, precio, cantidad, imagen FROM productos WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $stmt->bind_result($nombre, $descripcion, $precio, $cantidad, $imagen);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta SQL: " . $mysqli->error);
    }

    // Agregar el producto al carrito
    $sql = "INSERT INTO carrito (usuario_id, producto_id, nombre, descripcion, precio, cantidad, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("iississ", $usuario_id, $producto_id, $nombre, $descripcion, $precio, $cantidad, $imagen);
        if ($stmt->execute()) {
            header('Location: cart.php'); // Redirigir a la página del carrito
            exit();
        } else {
            die("Error al agregar el producto al carrito: " . $stmt->error);
        }
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta SQL: " . $mysqli->error);
    }
} else {
    die("ID de producto no especificado.");
}

$mysqli->close();
?>

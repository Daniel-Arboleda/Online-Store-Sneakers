<?php
require 'conexion.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Obtener el ID del usuario
if (!isset($_SESSION['user_id'])) {
    die("ID de usuario no disponible en la sesión.");
}
$usuario_id = $_SESSION['user_id'];

// Calcular el total del carrito
$total = 0;
$sql = "SELECT precio, cantidad FROM carrito WHERE usuario_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($precio, $cantidad);

    while ($stmt->fetch()) {
        $total += $precio * $cantidad;
    }
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

// Insertar la factura
$sql = "INSERT INTO facturas (usuario_id, total) VALUES (?, ?)";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("id", $usuario_id, $total);
    if ($stmt->execute()) {
        // Obtener el ID de la factura recién creada
        $factura_id = $stmt->insert_id;
        
        // Vaciar el carrito
        $sql = "DELETE FROM carrito WHERE usuario_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $usuario_id);
            $stmt->execute();
        }

        // Redirigir a una página de confirmación o a la página principal
        header('Location: ver_facturas.php'); // Cambia a la página deseada
        exit();
    } else {
        die("Error al insertar la factura: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

$mysqli->close();
?>

<?php
session_start();
// require 'conexion.php';
require __DIR__ . '../config/conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $producto_id = $_POST['producto_id'];

    // Marcar el producto como eliminado
    $sql = "UPDATE productos SET eliminado = 1 WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $producto_id);
        
        if ($stmt->execute()) {
            echo "El producto ha sido eliminado exitosamente.";
            header('Location: ver_stock.php'); // Redirigir a la vista de productos después de eliminar
        } else {
            echo "Error al eliminar el producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta SQL: " . $mysqli->error;
    }
}

$mysqli->close();
?>

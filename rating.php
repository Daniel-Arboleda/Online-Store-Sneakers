<!-- rating.php -->



<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'conexion.php';

if (isset($_POST['producto_id'], $_POST['puntuacion'], $_POST['usuario_email'])) {
    $producto_id = $_POST['producto_id'];
    $puntuacion = $_POST['puntuacion'];
    $usuario_email = $_POST['usuario_email'];

    // Depuración: imprime los datos recibidos
    error_log("Producto ID: $producto_id, Puntuación: $puntuacion, Usuario Email: $usuario_email");

    // Obtener el user_id del usuario basado en su email
    $sql = "SELECT id FROM validation WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $usuario_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Insertar o actualizar la puntuación en la tabla ratings
        $sql_insert = "INSERT INTO ratings (producto_id, usuario_id, puntuacion) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE puntuacion = ?";
        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert->bind_param('iiii', $producto_id, $user_id, $puntuacion, $puntuacion);
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo "Puntuación registrada correctamente.";
        } else {
            echo "Error al registrar la puntuación.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $stmt_insert->close();
} else {
    echo "Faltan datos.";
}

$mysqli->close();
?>

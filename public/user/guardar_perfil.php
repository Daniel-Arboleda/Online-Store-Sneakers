<!-- guardar_perfil.php -->

<?php
// require 'conexion.php';
require __DIR__ . '/../../config/conexion.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['user_id']; // Asegúrate de que este valor esté en la sesión


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null; // Manejo opcional
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_SESSION['email']; // Obtener el email del usuario logueado

    // Preparar y ejecutar la consulta SQL para actualizar el perfil del usuario
    $sql = "INSERT INTO usuarios (usuario_id, email, nombre, apellido, fecha_nacimiento, direccion, telefono) 
            VALUES (?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            nombre = VALUES(nombre), 
            apellido = VALUES(apellido), 
            fecha_nacimiento = VALUES(fecha_nacimiento), 
            direccion = VALUES(direccion), 
            telefono = VALUES(telefono)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("issssss", $usuario_id, $email, $nombre, $apellido, $fecha_nacimiento, $direccion, $telefono);
        if ($stmt->execute()) {
            header("Location: perfil.php?status=success&message=Perfil guardado exitosamente.");
        } else {
            header("Location: perfil.php?status=danger&message=Error al guardar el perfil: " . $stmt->error);
        }
        $stmt->close();
    } else {
        header("Location: perfil.php?status=danger&message=Error en la preparación de la consulta SQL: " . $mysqli->error);
    }
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>

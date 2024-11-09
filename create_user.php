<?php

// Asegúrate de que 'conexion.php' esté configurado correctamente para usar estas variables
// require 'conexion.php';
require __DIR__ . '/config/conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Encriptar la contraseña usando password_hash
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Preparar y ejecutar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO autenticacion (email, password) VALUES (?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $email, $hashed_password);
        if ($stmt->execute()) {
            // Iniciar sesión automáticamente después de crear el usuario
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $email;

            // Redirigir al usuario a la página principal o dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error al crear el usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL: " . $mysqli->error;
    }
}

// Cerrar la conexión a la base de datos
$mysqli->close();
?>

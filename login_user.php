<?php
session_start(); // Inicia la sesión
require 'conexion.php'; // Incluye la conexión a la base de datos
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Preparar y ejecutar la consulta SQL para obtener el usuario
    $sql = "SELECT id, email, password FROM autenticacion WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $hashed_password = $row['password'];

            // Verificar la contraseña
            if (password_verify($password, $hashed_password)) {
                // Iniciar sesión y redirigir al usuario
                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_id'] = $row['id']; // Guarda el ID del usuario en la sesión

                // Redirigir al usuario a la página principal o dashboard
                header("Location: dashboard.php"); // Cambia a la página deseada
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "No existe usuario con ese email.";
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL: " . $mysqli->error;
    }
}
// Cerrar la conexión a la base de datos
$mysqli->close();
?>

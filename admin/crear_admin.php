<!-- crear_admin.php -->

<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Procesar el formulario de creación de administrador
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require __DIR__ . '/../config/conexion.php';
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Asignamos rol_id = 1 para el rol de admin
    $rol_id = 1;

    // Insertar nuevo administrador en la tabla autenticacion
    $sql = "INSERT INTO autenticacion (email, password, rol_id) VALUES (?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssi", $email, $hashed_password, $rol_id);
        if ($stmt->execute()) {
            echo "Administrador creado exitosamente.";
        } else {
            echo "Error al crear el administrador: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL: " . $mysqli->error;
    }

    $mysqli->close();
}
?>

<!-- Formulario de creación de administrador -->
<form action="crear_admin_backend.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="submit" value="Crear Administrador">
</form>

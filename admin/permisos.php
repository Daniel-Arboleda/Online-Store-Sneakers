<!-- permisos.php -->

<?php
// Iniciar la sesión y verificar si el usuario es admin
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Código para conectar a la base de datos y obtener usuarios y roles
include '../config/db_connect.php'; // Archivo de conexión

// Consulta para obtener todos los usuarios y sus roles
$query = "SELECT u.id, u.email, r.nombre_rol FROM usuarios u LEFT JOIN roles r ON u.rol_id = r.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos</title>
    <!-- Estilos y scripts -->
</head>
<body>
    <h2>Gestión de Permisos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Email</th>
                <th>Rol Actual</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['nombre_rol']; ?></td>
                <td>
                    <a href="editar_permisos.php?user_id=<?php echo $row['id']; ?>" class="btn btn-primary">Editar Permisos</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>


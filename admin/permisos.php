<!-- permisos.php -->

<?php
// Iniciar la sesión y verificar si el usuario es admin
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al login si no es admin
    exit();
}

// Código para conectar a la base de datos y obtener usuarios y roles
include '../config/conexion.php';

// Consulta para obtener todos los usuarios y sus roles
$query = "SELECT u.id, u.email, r.nombre_rol FROM autenticacion u LEFT JOIN roles r ON u.rol_id = r.id";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Gestión de Permisos</h2>
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID Usuario</th>
                            <th scope="col">Email</th>
                            <th scope="col">Rol Actual</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['nombre_rol']; ?></td>
                            <td>
                                <a href="editar_permisos.php?user_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Editar Permisos</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="dashboard.php" class="btn btn-secondary mt-3">Regresar al Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

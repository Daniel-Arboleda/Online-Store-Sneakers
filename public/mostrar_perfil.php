<?php
session_start();
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Consultar los datos del usuario
$sql = "SELECT nombre, apellido, fecha_nacimiento, direccion, telefono FROM usuarios WHERE email = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellido, $fecha_nacimiento, $direccion, $telefono);
    $stmt->fetch();
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h2>Perfil de Usuario</h2>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?>">
                <?php echo $_GET['message']; ?>
            </div>
        <?php endif; ?>

        <!-- Tabla para mostrar los datos del usuario -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($nombre); ?></td>
                    <td><?php echo htmlspecialchars($apellido); ?></td>
                    <td><?php echo htmlspecialchars($fecha_nacimiento); ?></td>
                    <td><?php echo htmlspecialchars($direccion); ?></td>
                    <td><?php echo htmlspecialchars($telefono); ?></td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-warning" href="perfil.php" role="button">Editar Perfil</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

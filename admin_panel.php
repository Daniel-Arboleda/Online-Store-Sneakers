<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol adecuado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php'); // Redirigir si no es admin
    exit();
}

// Incluir el archivo de conexión a la base de datos
require 'conexion.php';

// Obtener lista de usuarios
$sql = "SELECT id, email, nombre, apellido, estado FROM usuarios";
$result = $mysqli->query($sql);

// Lógica para desactivar o activar usuario
if (isset($_POST['accion']) && isset($_POST['usuario_id'])) {
    $accion = $_POST['accion'];
    $usuario_id = $_POST['usuario_id'];
    
    if ($accion == 'activar') {
        $sql = "UPDATE usuarios SET estado = 'activo' WHERE id = ?";
    } elseif ($accion == 'desactivar') {
        $sql = "UPDATE usuarios SET estado = 'inactivo' WHERE id = ?";
    }

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error al actualizar el estado del usuario.";
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="container mt-5">
        <h2>Gestión de Usuarios</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['estado']); ?></td>
                        <td>
                            <!-- Formulario para activar o desactivar al usuario -->
                            <form action="admin_panel.php" method="POST" style="display:inline;">
                                <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                <button type="submit" name="accion" value="activar" class="btn btn-success">Activar</button>
                                <button type="submit" name="accion" value="desactivar" class="btn btn-danger">Desactivar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

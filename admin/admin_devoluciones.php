<!-- admin_devoluciones.php -->



<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Incluir la conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';

// Obtener devoluciones pendientes
$sql = "SELECT d.id, d.pedido_id, d.usuario_id, d.fecha_solicitud, d.motivo, u.nombre, u.apellido
        FROM devoluciones d
        JOIN usuarios u ON d.usuario_id = u.id
        WHERE d.estado = 'pendiente'
        ORDER BY d.fecha_solicitud DESC";

$result = $mysqli->query($sql);

// Procesar cambios de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $devolucion_id = $_POST['devolucion_id'];
    $estado = $_POST['estado'];
    $respuesta_admin = $_POST['respuesta_admin'];

    $update_sql = "UPDATE devoluciones SET estado = ?, respuesta_admin = ?, fecha_resolucion = NOW() WHERE id = ?";
    $stmt = $mysqli->prepare($update_sql);
    $stmt->bind_param('ssi', $estado, $respuesta_admin, $devolucion_id);
    if ($stmt->execute()) {
        $mensaje = "Devolución actualizada correctamente.";
    } else {
        $mensaje = "Error al actualizar la devolución.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Devoluciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_admin.php'; ?>

    <div class="container mt-5">
        <h2>Gestión de Devoluciones</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Devolución</th>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha de Solicitud</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['pedido_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($row['motivo']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="devolucion_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="estado" value="aprobada">
                                <textarea name="respuesta_admin" placeholder="Respuesta..." required></textarea>
                                <button type="submit" class="btn btn-success">Aprobar</button>
                            </form>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="devolucion_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="estado" value="rechazada">
                                <textarea name="respuesta_admin" placeholder="Respuesta..." required></textarea>
                                <button type="submit" class="btn btn-danger">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

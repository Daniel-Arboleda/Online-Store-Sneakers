<!-- admin_pedidos.php -->


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

// Obtener pedidos pendientes
$sql = "SELECT p.id, p.usuario_id, p.fecha, p.estado, p.total, u.nombre, u.apellido
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.estado = 'pendiente'
        ORDER BY p.fecha DESC";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedidos Pendientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_admin.php'; ?>

    <div class="container mt-5">
    <a href="historial_pedidos.php" class="btn btn-secondary">Historial de Pedidos</a>
    </div>

    <div class="container mt-5">
        <h2>Pedidos Pendientes</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($row['total']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($row['estado']); ?>
                        </td>
                        <td>
                            <a href="ver_detalles_pedido.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Ver Detalles</a>
                            <a href="actualizar_estado.php?id=<?php echo $row['id']; ?>&estado=en_proceso" class="btn btn-warning">Marcar como En Proceso</a>
                            <a href="actualizar_estado.php?id=<?php echo $row['id']; ?>&estado=cancelado" class="btn btn-danger">Cancelar Pedido</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

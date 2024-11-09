<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.html');
    exit();
}

// Incluir la conexión a la base de datos
require 'conexion.php';

// Consultar todos los cupones activos
$sql = "SELECT * FROM cupones ORDER BY fecha_inicio DESC";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error al consultar los cupones: " . $mysqli->error);
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Cupones</title>
</head>
<body>
    <h2>Administrar Cupones</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Código</th>
                <th>Tipo de descuento</th>
                <th>Valor</th>
                <th>Fecha de inicio</th>
                <th>Fecha de fin</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($row['tipo_descuento']); ?></td>
                    <td><?php echo htmlspecialchars($row['valor']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                    <td><?php echo $row['activo'] ? 'Sí' : 'No'; ?></td>
                    <td>
                        <a href="editar_cupon.php?id=<?php echo $row['id']; ?>">Editar</a> |
                        <a href="eliminar_cupon.php?id=<?php echo $row['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="crear_cupon.php">Crear nuevo cupón</a>
</body>
</html>

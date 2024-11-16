<!-- ver_cupones.php -->

<?php
session_start();
require __DIR__ . '/../config/conexion.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM cupones ORDER BY fecha_inicio DESC";
$result = $mysqli->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $tipo_descuento = $_POST['tipo_descuento'];
    $valor = $_POST['valor'];
    $productos_aplicables = $_POST['productos_aplicables'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Manejo de valores NULL para fecha_inicio y fecha_fin
    $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
    $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;

    $sql_insert = "INSERT INTO cupones (codigo, tipo_descuento, valor, fecha_inicio, fecha_fin, productos_aplicables, activo) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)
                   ON DUPLICATE KEY UPDATE 
                   codigo = VALUES(codigo), 
                   tipo_descuento = VALUES(tipo_descuento), 
                   valor = VALUES(valor), 
                   fecha_inicio = VALUES(fecha_inicio), 
                   fecha_fin = VALUES(fecha_fin),
                   productos_aplicables = VALUES(productos_aplicables),
                   activo = VALUES(activo)";

    if ($stmt = $mysqli->prepare($sql_insert)) {
        // Usamos "isssssi" para especificar los tipos: string, integer, y demás.
        $stmt->bind_param("ssisssi", $codigo, $tipo_descuento, $valor, $fecha_inicio, $fecha_fin, $productos_aplicables, $activo);
        
        if ($stmt->execute()) {
            header("Location: ver_cupones.php?status=success&message=Cupón guardado exitosamente.");
        } else {
            header("Location: ver_cupones.php?status=danger&message=Error al guardar el cupón: " . $stmt->error);
        }
        $stmt->close();
    } else {
        header("Location: ver_cupones.php?status=danger&message=Error en la preparación de la consulta SQL: " . $mysqli->error);
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Cupones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    <div class="container mt-5">
        <h2>Gestionar Cupones</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Inicio</th>
                    <th>Fin</th>
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

        <hr>

        <h3>Crear Nuevo Cupón</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código:</label>
                <input type="text" name="codigo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tipo_descuento" class="form-label">Tipo:</label>
                <select name="tipo_descuento" class="form-select">
                    <option value="porcentaje">Porcentaje</option>
                    <option value="monto_fijo">Monto Fijo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="valor" class="form-label">Valor:</label>
                <input type="number" name="valor" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" class="form-control" placeholder="dd/mm/aaaa" required>
            </div>
            <div class="mb-3">
                <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                <input type="date" name="fecha_fin" class="form-control" placeholder="dd/mm/aaaa" required>
            </div>
            <div class="mb-3">
                <label for="productos_aplicables" class="form-label">Productos Aplicables:</label>
                <textarea name="productos_aplicables" class="form-control"></textarea>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="activo" class="form-check-input" id="activo" checked>
                <label class="form-check-label" for="activo">Activo</label>
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

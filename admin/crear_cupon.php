<?php
session_start();
require __DIR__ . '/../config/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Procesar el formulario de creación de cupones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $tipo_descuento = $_POST['tipo_descuento'];
    $valor = $_POST['valor'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $productos_aplicables = $_POST['productos_aplicables'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Preparar la consulta para insertar el cupón en la base de datos
    $sql = "INSERT INTO cupones (codigo, tipo_descuento, valor, fecha_inicio, fecha_fin, productos_aplicables, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssdsdsi", $codigo, $tipo_descuento, $valor, $fecha_inicio, $fecha_fin, $productos_aplicables, $activo);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Cupón creado exitosamente.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error al crear el cupón: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error en la preparación de la consulta: " . $mysqli->error . "</div>";
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cupón de Descuento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Crear Cupón de Descuento</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código del cupón:</label>
                <input type="text" name="codigo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tipo_descuento" class="form-label">Tipo de descuento:</label>
                <select name="tipo_descuento" class="form-select">
                    <option value="porcentaje">Porcentaje</option>
                    <option value="monto_fijo">Monto Fijo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="valor" class="form-label">Valor del descuento:</label>
                <input type="number" name="valor" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="fecha_fin" class="form-label">Fecha de fin:</label>
                <input type="date" name="fecha_fin" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="productos_aplicables" class="form-label">Productos aplicables (opcional):</label>
                <textarea name="productos_aplicables" class="form-control"></textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="activo" class="form-check-input" id="activo" checked>
                <label class="form-check-label" for="activo">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary">Crear Cupón</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

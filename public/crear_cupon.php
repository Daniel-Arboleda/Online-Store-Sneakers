<?php
session_start();

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.html');
    exit();
}

// Incluir conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';


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
            echo "Cupón creado exitosamente.";
        } else {
            echo "Error al crear el cupón: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $mysqli->error;
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
</head>
<body>
    <h2>Crear Cupón de Descuento</h2>
    <form method="POST">
        <label for="codigo">Código del cupón:</label>
        <input type="text" name="codigo" required><br>

        <label for="tipo_descuento">Tipo de descuento:</label>
        <select name="tipo_descuento">
            <option value="porcentaje">Porcentaje</option>
            <option value="monto_fijo">Monto Fijo</option>
        </select><br>

        <label for="valor">Valor del descuento:</label>
        <input type="number" name="valor" step="0.01" required><br>

        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" required><br>

        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" name="fecha_fin" required><br>

        <label for="productos_aplicables">Productos aplicables (opcional):</label>
        <textarea name="productos_aplicables"></textarea><br>

        <label for="activo">Activo:</label>
        <input type="checkbox" name="activo" checked><br>

        <button type="submit">Crear Cupón</button>
    </form>
</body>
</html>

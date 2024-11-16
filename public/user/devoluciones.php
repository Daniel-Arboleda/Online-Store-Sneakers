<!-- devoluciones.php -->



<?php
session_start(); // Iniciar la sesión
// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php'); // Redirigir al formulario de login si no está autenticado
    exit();
}

// Incluir la conexión a la base de datos
require __DIR__ . '/../../config/conexion.php';

// Procesar formulario de devolución
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $motivo = $_POST['motivo'];
    $usuario_id = $_SESSION['usuario_id'];

    // Validar que el pedido pertenezca al usuario y esté completado
    $validacion_sql = "SELECT id FROM pedidos WHERE id = ? AND usuario_id = ? AND estado = 'completado'";
    $stmt = $mysqli->prepare($validacion_sql);
    $stmt->bind_param('ii', $pedido_id, $usuario_id);
    $stmt->execute();
    $validacion_result = $stmt->get_result();

    if ($validacion_result->num_rows > 0) {
        // Insertar solicitud de devolución
        $insert_sql = "INSERT INTO devoluciones (pedido_id, usuario_id, motivo) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insert_sql);
        $stmt->bind_param('iis', $pedido_id, $usuario_id, $motivo);
        if ($stmt->execute()) {
            $mensaje = "Solicitud de devolución enviada correctamente.";
        } else {
            $mensaje = "Error al enviar la solicitud.";
        }
    } else {
        $mensaje = "El pedido no es válido para devolución.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Devolución</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h2>Solicitar Devolución</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="pedido_id" class="form-label">ID del Pedido</label>
                <input type="number" name="pedido_id" id="pedido_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="motivo" class="form-label">Motivo de la Devolución</label>
                <textarea name="motivo" id="motivo" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>
    </div>
</body>
</html>

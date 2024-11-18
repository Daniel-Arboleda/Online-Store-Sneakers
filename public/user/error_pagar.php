<!-- error_pagar.php -->




<?php
session_start();
$mensaje_error = isset($_SESSION['mensaje_error']) ? $_SESSION['mensaje_error'] : "Ha ocurrido un error inesperado.";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1>Error</h1>
    <p><?php echo htmlspecialchars($mensaje_error); ?></p>
    <a href="dashboard.php">Volver al inicio</a>
</body>
</html>

<?php
session_start(); // Iniciar la sesión
// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html'); // Redirigir al formulario de login si no está autenticado
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StoreThays</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js">
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Esto incluye el archivo de menú -->   
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
        <p>Contenido protegido.</p>
        <a href="logout.php" class="btn btn-primary">Cerrar sesión</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

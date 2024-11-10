<!-- dashboard.php -->

<?php
session_start(); // Iniciar la sesión
// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php'); // Redirigir al formulario de login si no está autenticado
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Encabezado con opciones de usuario -->
    <?php include 'menu.php'; ?> <!-- Menú de navegación principal -->
    
    <div class="container my-5">
        <!-- Saludo de bienvenida -->
        <h1 class="mb-4">Hola, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
        
        <!-- Panel de Resumen -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Mis Pedidos</div>
                    <div class="card-body">
                        <h5 class="card-title">5 Pedidos</h5>
                        <p class="card-text">Consulta el estado de tus pedidos recientes.</p>
                        <a href="mis_pedidos.php" class="btn btn-light">Ver pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">Mi Carrito</div>
                    <div class="card-body">
                        <h5 class="card-title">3 Artículos</h5>
                        <p class="card-text">Revisa y gestiona los artículos en tu carrito.</p>
                        <a href="cart.php" class="btn btn-light">Ver carrito</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Perfil</div>
                    <div class="card-body">
                        <h5 class="card-title">Actualiza tus datos</h5>
                        <p class="card-text">Gestiona tu información de perfil y dirección.</p>
                        <a href="perfil.php" class="btn btn-light">Ir al perfil</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Opciones de sesión en el encabezado -->
        <!-- <div class="d-flex justify-content-end">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="userMenu">
                    <a class="dropdown-item" href="perfil.php">Mi perfil</a>
                    <a class="dropdown-item" href="configuracion.php">Configuración</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

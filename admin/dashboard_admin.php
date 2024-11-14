<!-- dashboard_admin.php -->

<?php

session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Incluir el archivo de conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <?php include 'menu_admin.php'; ?>
    
    <div class="container my-5">
        <h1>Bienvenido al Dashboard de Administración, <?php echo htmlspecialchars($_SESSION['email']); ?></h1>
        <p>Desde aquí, puedes gestionar la tienda y ver las estadísticas generales.</p>
        
        <div class="row">
            <!-- Métricas -->
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Ventas Diarias</div>
                    <div class="card-body">
                        <h5 class="card-title">$1,500</h5>
                        <p class="card-text">Total de ventas de hoy.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Pedidos Pendientes</div>
                    <div class="card-body">
                        <h5 class="card-title">23</h5>
                        <p class="card-text">Pedidos que necesitan atención.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Inventario Bajo</div>
                    <div class="card-body">
                        <h5 class="card-title">8 Productos</h5>
                        <p class="card-text">Artículos que necesitan reabastecimiento.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opciones de Administración -->
        <div class="row">
            <div class="col-md-4">
                <a href="admin_productos.php" class="btn btn-primary btn-block">Gestionar Productos</a>
            </div>
            <div class="col-md-4">
                <a href="admin_usuarios.php" class="btn btn-primary btn-block">Gestionar Usuarios</a>
            </div>
            <div class="col-md-4">
                <a href="admin_pedidos.php" class="btn btn-primary btn-block">Ver Pedidos</a>
            </div>
        </div>

        <!-- Estadísticas generales -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3>Estadísticas Generales</h3>
                <p>Aquí puedes ver las estadísticas de ventas, usuarios activos, y más.</p>
                <!-- Aquí se podrían agregar gráficos o tablas con estadísticas -->
            </div>
        </div>
    </div>
    
    <!-- jQuery y Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Activar submenús -->
    <script>
        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });
    </script>
</body>
</html>

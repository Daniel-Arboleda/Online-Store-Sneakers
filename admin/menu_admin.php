<!-- menu_admin.php -->
<?php
// Iniciar sesión al principio del archivo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['email']);
$usuarioEmail = $isLoggedIn ? $_SESSION['email'] : '';
?>

<!-- Incluir Bootstrap CSS y JS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">TIENDA SNEAKERS - ADMIN</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex justify-content-between align-items-center" id="navbarNav">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard_admin.php">Dashboard</a>
            </li>

            <!-- Usuarios -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUsuarios" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Usuarios
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownUsuarios">
                    <a class="dropdown-item" href="admin_usuarios.php">Ver Usuarios</a>
                    <a class="dropdown-item" href="permisos.php">Permisos</a>
                    <a class="dropdown-item" href="usuarios_bloqueados.php">Usuarios Bloqueados</a>
                </div>
            </li>

            <!-- Tienda -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTienda" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tienda
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownTienda">
                    <!-- Gestión de Productos -->
                    <!-- <a class="dropdown-item" href="agregar_producto.php">Crear Producto</a> -->
                    <a class="dropdown-item" href="admin_productos.php">Crear/Ver/Editar Productos & Stock</a>
                    <!-- <a class="dropdown-item" href="ver_stock.php">Ver/Editar Stock</a> -->
                    <!-- <a class="dropdown-item" href="admin_categorias.php">Categorías</a> -->
                    
                    <!-- Gestión de Cupones -->
                    <!-- <a class="dropdown-item" href="crear_cupon.php">Crear Cupón</a> -->
                    <a class="dropdown-item" href="ver_cupones.php">Crear/Ver/Editar Cupones</a>

                    <!-- Gestión de Pedidos y Devoluciones -->
                    <a class="dropdown-item" href="admin_pedidos.php">Pedidos Pendientes</a>
                    <a class="dropdown-item" href="historial_pedidos.php">Historial de Pedidos</a>
                    <a class="dropdown-item" href="admin_devoluciones.php">Gestión de Devoluciones</a>
                </div>
            </li>

            <!-- Comentarios y Valoraciones -->
            <li class="nav-item">
                <a class="nav-link" href="admin_comentarios.php">Comentarios</a>
            </li>

            <!-- Reportes -->
            <li class="nav-item">
                <a class="nav-link" href="reportes.php">Reportes</a>
            </li>

            <!-- Configuración -->
            <li class="nav-item">
                <a class="nav-link" href="configuracion.php">Configuración</a>
            </li>
        </ul>
    </div>
    
    <!-- Usuario y Cierre de Sesión -->
    <div class="d-flex align-items-center">
        <?php if ($isLoggedIn): ?>
            <p class="alert alert-warning mb-0 mr-3"> Usuario: <?php echo htmlspecialchars($usuarioEmail); ?>!</p>
        <?php else: ?>
            <p class="alert alert-warning mb-0 mr-3"> No has iniciado sesión.</p>
        <?php endif; ?>
        
        <form class="form-inline my-2 my-lg-0 mr-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>

        <?php if ($isLoggedIn): ?>
            <a class="btn btn-warning" href="../auth/logout.php" role="button">Cerrar sesión</a>
        <?php else: ?>
            <a class="btn btn-warning" href="login.php" role="button">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</nav>

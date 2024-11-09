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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">TIENDA SNEAKERS - ADMIN</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex justify-content-between align-items-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
            </li>
            <!-- Gestión de Productos -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProductos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Productos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownProductos">
                    <a class="dropdown-item" href="agregar_producto.php">Crear Producto</a>
                    <a class="dropdown-item" href="admin_productos.php">Ver/Editar Productos</a>
                    <a class="dropdown-item" href="admin_categorias.php">Categorías</a>
                </div>
            </li>
            <!-- Gestión de Usuarios -->
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
            <!-- Gestión de Pedidos -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPedidos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pedidos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownPedidos">
                    <a class="dropdown-item" href="admin_pedidos.php">Pedidos Pendientes</a>
                    <a class="dropdown-item" href="historial_pedidos.php">Historial de Pedidos</a>
                    <a class="dropdown-item" href="devoluciones.php">Gestión de Devoluciones</a>
                </div>
            </li>
            <!-- Cupones y Descuentos -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCupones" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cupones
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCupones">
                    <a class="dropdown-item" href="crear_cupon.php">Crear Cupón</a>
                    <a class="dropdown-item" href="ver_cupones.php">Ver/Editar Cupones</a>
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
            <a class="btn btn-warning" href="logout.php" role="button">Cerrar sesión</a>
        <?php else: ?>
            <a class="btn btn-warning" href="login.php" role="button">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</nav>

<!-- menu.php -->
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
    <a class="navbar-brand" href="#">TIENDA SNEAKERS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex justify-content-between align-items-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <!-- Sección de Perfil -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPerfil" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Perfil
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownPerfil">
                    <a class="dropdown-item" href="mostrar_perfil.php">Ver Perfil</a>
                    <a class="dropdown-item" href="perfil.php">Editar Perfil</a>
                </div>
            </li>
            <!-- Tienda -->
            <li class="nav-item">
                <a class="nav-link" href="tienda.php">Tienda</a>
            </li>
            <!-- Carrito de Compras -->
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Carrito</a>
            </li>
            <!-- Sección de Pedidos -->
            <li class="nav-item">
                <a class="nav-link" href="mis_pedidos.php">Pedidos</a>
            </li>
            <!-- Historial de Facturas -->
            <li class="nav-item">
                <a class="nav-link" href="ver_facturas.php">Facturas</a>
            </li>
            <!-- Devoluciones -->
            <li class="nav-item">
                <a class="nav-link" href="devoluciones.php">Devoluciones</a>
            </li>
        </ul>
    </div>
    <!-- Usuario y búsqueda -->
    <div class="d-flex align-items-center">
        <?php if ($isLoggedIn): ?>
            <p class="alert alert-warning mb-0 mr-3"> Usuario: <?php echo htmlspecialchars($usuarioEmail); ?></p>
        <?php else: ?>
            <p class="alert alert-warning mb-0 mr-3"> No has iniciado sesión.</p>
        <?php endif; ?>
        <form class="form-inline my-2 my-lg-0 mr-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
        <?php if ($isLoggedIn): ?>
            <a class="btn btn-warning" href="../../auth/logout.php" role="button">Cerrar sesión</a>
        <?php else: ?>
            <a class="btn btn-warning" href="login.php" role="button">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</nav>

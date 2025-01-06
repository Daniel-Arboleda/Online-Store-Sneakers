<!-- dashboard.php -->

<?php
session_start(); // Iniciar la sesión
// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php'); // Redirigir al formulario de login si no está autenticado
    exit();
}

$usuario_id = $_SESSION['user_id']; // ID del usuario logueado

// Conexión a la base de datos
require __DIR__ . '/../../config/conexion.php'; // Verifica que este archivo sea correcto


// Inicializamos la variable para la cantidad de artículos
$cantidad_articulos = 0;

try {
    // Consulta para contar la cantidad de artículos únicos en el carrito del usuario
    $sql = "SELECT COUNT(DISTINCT producto_id) AS cantidad_articulos 
            FROM carrito_item
            WHERE carrito_id IN (
                SELECT id FROM carrito WHERE usuario_id = ?
            )";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $usuario_id); // Usamos bind_param para mysqli si usas 's':'string y 'i':'integer'
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Si se obtienen resultados, asignamos el valor
    $cantidad_articulos = $data['cantidad_articulos'] ?? 0;
} catch (Exception $e) {
    // Manejo de errores
    echo "Error al realizar la consulta: " . $e->getMessage();
    exit();
}


// Inicializamos la variable para la cantidad de pedidos
$cantidad_pedidos = 0;

try {
    // Consulta para contar la cantidad de pedidos del usuario
    $sql_pedidos = "SELECT COUNT(*) AS cantidad_pedidos FROM pedidos WHERE usuario_id = ?";
    $stmt_pedidos = $mysqli->prepare($sql_pedidos);
    $stmt_pedidos->bind_param('i', $usuario_id);
    $stmt_pedidos->execute();
    $result_pedidos = $stmt_pedidos->get_result();
    $data_pedidos = $result_pedidos->fetch_assoc();

    // Si se obtienen resultados, asignamos el valor
    $cantidad_pedidos = $data_pedidos['cantidad_pedidos'] ?? 0;
} catch (Exception $e) {
    // Manejo de errores
    echo "Error al realizar la consulta de pedidos: " . $e->getMessage();
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
                        <h5 class="card-title"><?php echo $cantidad_pedidos; ?> Pedidos</h5>
                        <p class="card-text">Consulta el estado de tus pedidos recientes.</p>
                        <a href="mis_pedidos.php" class="btn btn-light">Ver pedidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">Mi Carrito</div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $cantidad_articulos; ?> Artículos</h5>
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
        
    </div>
      

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!-- carrito_flotante.php -->

<?php
// Conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/config/conexion.php';


// Verificar si hay productos en el carrito del usuario

// Verifica si la sesión ya está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión solo si no está activa
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Consulta modificada para usar la columna 'usuario_id'
    $sql = "SELECT p.nombre, p.precio, ci.cantidad, (p.precio * ci.cantidad) AS total
            FROM carrito_item ci
            JOIN productos p ON ci.producto_id = p.id
            WHERE ci.usuario_id = ?"; // Modificado para usar 'usuario_id'
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $items = [];
    $total = 0;

    // Recopilar los productos y calcular el total
    while ($row = $resultado->fetch_assoc()) {
        $items[] = $row;
        $total += $row['total'];
    }
}
?>

<!-- Carrito Flotante -->
<div id="floating-cart" class="fixed-bottom mb-4 mr-4">
    <div class="card" style="max-width: 300px;">
        <div class="card-body">
            <h5 class="card-title">Carrito</h5>
            <ul class="list-group list-group-flush">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><?php echo htmlspecialchars($item['nombre']); ?></span>
                                <span><?php echo '$' . number_format($item['total'], 2); ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong><?php echo '$' . number_format($total, 2); ?></strong>
                    </li>
                <?php else: ?>
                    <li class="list-group-item">No hay productos en el carrito</li>
                <?php endif; ?>
            </ul>
            <a href="cart.php" class="btn btn-primary btn-block">Ver Carrito</a>
        </div>
    </div>
</div>

<!-- Botón flotante para mostrar el carrito -->
<button id="show-cart-btn" class="btn btn-danger rounded-circle">
    <i class="bi bi-cart"></i> 
</button>

<script>
    // Mostrar el carrito flotante
    document.getElementById('show-cart-btn').addEventListener('click', function() {
        const cart = document.getElementById('floating-cart');
        cart.style.display = cart.style.display === 'none' || cart.style.display === '' ? 'block' : 'none';
    });

    // Asegurarse de que el carrito esté visible al cargar la página
    window.onload = function() {
        document.getElementById('floating-cart').style.display = 'none'; // Ocultar inicialmente
    };
</script>

<!-- Estilos del carrito flotante -->
<style>
    #floating-cart {
        display: none;
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 250px;
        z-index: 1000;
    }
    #show-cart-btn {
        position: fixed;
        bottom: 20px;
        right: 10px;
        z-index: 1001;
    }
</style>

<?php
// Cerrar la conexión a la base de datos
$mysqli->close();
?>

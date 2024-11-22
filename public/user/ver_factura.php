<!-- ver_factura.php -->


<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Incluir la lógica de factura.php
require __DIR__ . '/models/factura.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-3">Factura</h2>
        <p><strong>Factura ID:</strong> <?php echo htmlspecialchars($factura_id); ?></p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($usuario->getNombre() . ' ' . $usuario->getApellido()); ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($usuario->getEmail()); ?></p>
        <p><strong>Dirección de envío:</strong> <?php echo htmlspecialchars($usuario->getDireccion()); ?></p>
        <h2 class="text-center my-3">Detalle de la Factura</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>$<?php echo number_format($item['cantidad'] * $item['precio'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="text-end"><strong>Total a pagar:</strong> $<?php echo number_format($total, 2); ?></p>
    </div>

    <!-- Botón para pagar con estilos Bootstrap -->
    <button class="btn btn-primary" onclick="procesarPago(<?php echo $factura_id; ?>)">¡Pagar Ahora!</button>

    <script>
        function procesarPago(facturaId) {
            // Realiza una solicitud POST usando fetch para procesar el pago
            fetch('http://localhost/Online-Store-Sneakers/public/user/models/procesar_pago.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ factura_id: facturaId })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Datos enviados:', { factura_id: facturaId });
                console.log('Respuesta recibida:', data);
                if (data.success) {
                    alert(data.message);
                    window.close(); // Cierra la ventana modal
                    window.opener.location.href = 'dashboard.php?message=' + encodeURIComponent(data.message); // Redirige al dashboard
                } else {
                    alert('Error al procesar el pago: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error en el servidor:', error);
                alert('Error en el servidor: ' + error.message);
            });
        }

    </script>

</body>
</html>
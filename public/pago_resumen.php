<!-- pago_resumen.php -->

<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require 'conexion.php';
require 'paypal_config.php';

// Obtener el carrito del usuario y calcular el total
$total = 0;
// Aquí debes calcular el total del carrito usando los datos de la sesión o la base de datos

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Pago</title>
    <script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=USD"></script>
</head>
<body>
    <h1>Resumen de Pago</h1>
    <p>Total a pagar: $<?php echo number_format($total, 2); ?></p>

    <!-- Botón de PayPal -->
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $total; ?>'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Redirigir al usuario a la página de éxito
                    window.location.href = "pago_exitoso.php?orderID=" + data.orderID;
                });
            },
            onError: function(err) {
                console.error("Error en el proceso de pago:", err);
                alert("Hubo un problema al procesar el pago.");
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>

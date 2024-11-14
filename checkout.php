<!-- Checkout con Mercadopago -->
<?php
require '/config/config.php';



// Crear preferencia de pago
$preference = new MercadoPago\Preference($_ENV['MERCADOPAGO_PUBLIC_KEY']);

// Definir los ítems de la compra
$item = new MercadoPago\Item();
$item->title = 'Nombre del Producto';
$item->quantity = 1;
$item->unit_price = 75.56; // Precio en la moneda configurada

$preference->items = array($item);

// URL de retorno
$preference->back_urls = array(
    "success" => "https://tusitio.com/exito",
    "failure" => "https://tusitio.com/error",
    "pending" => "https://tusitio.com/pendiente"
);
$preference->auto_return = "approved";

// Guardar la preferencia
$preference->save();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pago con MercadoPago</title>
    <!-- Incluir el script del botón de MercadoPago -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div id="wallet_container">
        <script>
            const mp = new MercadoPago($_ENV['MERCADOPAGO_PUBLIC_KEY']);
            mp.bricks().create("wallet", "wallet_container", {
                initialization: {
                    preferenceId:'<?php echo $preference->id; ?>'
                }
            })
        </script>

    </div>
    <h1>Pagar con MercadoPago</h1>
    <!-- Botón de MercadoPago -->
    <div id="button-checkout"></div>

    <script>
        // Inicializar MercadoPago con tu Public Key
        const mp = new MercadoPago($_ENV['MERCADOPAGO_PUBLIC_KEY'], { locale: 'es-MX' });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render: {
                container: '#button-checkout', // Indica dónde se debe renderizar el botón
                label: 'Pagar con MercadoPago' // Personaliza el texto del botón
            }
        });
    </script>
</body>
</html>
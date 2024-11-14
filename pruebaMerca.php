<?php

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
require 'vendor/autoload.php';

MercadoPagoConfig::setAccessToken("TEST-1779176051186418-111023-ff56be2febaef0332da0e281eab8617b-2091124386");

$client = new PreferenceClient();

$preference =$client->create([
    "items" => [
        [
            "id" => "DEP-001",
            "title" => "",
            "quantity" => 1,
            "unit_price" => 100.00 
        ],
    ],
    "player"=>[
        // Correo del usuario de prueba
        "email" => "do@do.co"
    ],

    "statement_descriptor" => "Tienda-Sneakers",
    "external_reference" => "CDP001"
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SDK MercadoPago.js -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script type="text/javascript" src="/public/js/mercadoPago.js" defer></script>
    <title>Document</title>
</head>
<body>
    <!-- Contenedor para renderizar el botón de MercadoPago -->
    <div id="wallet_container">
    </div>
    <script>
        const mp = new MercadoPago('TEST-c4057524-8841-4c8f-8304-d497aecc89a0', {
            locale: 'es-CO'
        });

        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                // preferenceId: "<PREFERENCE_ID>",
                preferenceId: "<?php echo $preference->id; ?>",
                redirecMode: 'modal',

            },
        customization: {
            text: {
                action: 'buy',
                valueProp: 'smart_option',
            },

        },
        });
    </script>
</body>
</html>
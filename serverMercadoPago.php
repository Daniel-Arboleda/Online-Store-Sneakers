<?php

require __DIR__  . '/vendor/autoload.php';

//REPLACE WITH YOUR ACCESS TOKEN AVAILABLE IN: https://developers.mercadopago.com/panel/credentials
MercadoPago\SDK::setAccessToken("APP_USR-569145724370318-111023-dd12d2e189ea0f519aa51129c138a5a2-2088531771");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch($path){
    case '':
    case '/':
        require __DIR__ . '/public/js/mercadoPago.html';
        break;
    case '/create_preference':
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $preference = new MercadoPago\Preference();

        $item = new MercadoPago\Item();
        $item->title = $data->description;
        $item->quantity = $data->quantity;
        $item->unit_price = $data->price;

        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => "http://localhost:8080/dashboard.php",
            "failure" => "http://localhost:8080/pruebaMercado.php", 
            "pending" => "http://localhost:8080/cart.php"
        );
        $preference->auto_return = "approved"; 

        $preference->save();

        $response = array(
            'id' => $preference->id,
        ); 
        echo json_encode($response);
        break;        
    case '/feedback':
        $respuesta = array(
            'Payment' => $_GET['payment_id'],
            'Status' => $_GET['status'],
            'MerchantOrder' => $_GET['merchant_order_id']        
        ); 
        echo json_encode($respuesta);
        break;
    //Server static resources
    default:
        $file = __DIR__ . '/public' . $path;
        $extension = end(explode('.', $path));
        $content = 'text/html';
        switch($extension){
            case 'js': $content = 'application/javascript'; break;
            case 'css': $content = 'text/css'; break;
            case 'png': $content = 'image/png'; break;
        }
        header('Content-Type: '.$content);
        readfile($file);          
}
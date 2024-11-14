<?php
require __DIR__ . "/../vendor/autoload.php";

use PaypalServerSDKLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSDKLib\Environment;
use PaypalServerSDKLib\PaypalServerSDKClientBuilder;
use PaypalServerSDKLib\Models\Builders\MoneyBuilder;
use PaypalServerSDKLib\Models\Builders\OrderRequestBuilder;
use PaypalServerSDKLib\Models\Builders\PurchaseUnitRequestBuilder;
use PaypalServerSDKLib\Models\Builders\AmountWithBreakdownBuilder;
use PaypalServerSDKLib\Models\Builders\ShippingDetailsBuilder;
use PaypalServerSDKLib\Models\Builders\ShippingOptionBuilder;
use PaypalServerSDKLib\Models\ShippingType;

$PAYPAL_CLIENT_ID = getenv("PAYPAL_CLIENT_ID");
$PAYPAL_CLIENT_SECRET = getenv("PAYPAL_CLIENT_SECRET");


$client = PaypalServerSdkClientBuilder::init()
    ->clientCredentialsAuthCredentials(
        ClientCredentialsAuthCredentialsBuilder::init(
            $PAYPAL_CLIENT_ID,
            $PAYPAL_CLIENT_SECRET
        )
    )
    ->environment(Environment::SANDBOX)
    ->build();


function handleResponse($response)
{
    $jsonResponse = json_decode($response->getBody(), true);
    return [
        "jsonResponse" => $jsonResponse,
        "httpStatusCode" => $response->getStatusCode(),
    ];
}

$endpoint = $_SERVER["REQUEST_URI"];
if ($endpoint === "/") {
    try {
        $response = [
            "message" => "Server is running",
        ];
        header("Content-Type: application/json");
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }
} 

/**
 * Create an order to start the transaction.
 * @see https://developer.paypal.com/docs/api/orders/v2/#orders_create
 */
function createOrder($cart)
{
    global $client;

    $orderBody = [
        "body" => OrderRequestBuilder::init("CAPTURE", [
            PurchaseUnitRequestBuilder::init(
                AmountWithBreakdownBuilder::init("USD", "100")->build()
            )->build(),
        ])->build(),
    ];

    
    $apiResponse = $client->getOrdersController()->ordersCreate($orderBody);

    return handleResponse($apiResponse);
}

if ($endpoint === "/api/orders") {
    $data = json_decode(file_get_contents("php://input"), true);
    $cart = $data["cart"];
    header("Content-Type: application/json");
    try {
        $orderResponse = createOrder($cart);
        echo json_encode($orderResponse["jsonResponse"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }
}


/**
 * Capture payment for the created order to complete the transaction.
 * @see https://developer.paypal.com/docs/api/orders/v2/#orders_capture
 */
function captureOrder($orderID)
{
    global $client;

    $captureBody = [
        "id" => $orderID,
    ];

    $apiResponse = $client->getOrdersController()->ordersCapture($captureBody);

    return handleResponse($apiResponse);
}

if (str_ends_with($endpoint, "/capture")) {
    $urlSegments = explode("/", $endpoint);
    end($urlSegments); // Will set the pointer to the end of array
    $orderID = prev($urlSegments);
    header("Content-Type: application/json");
    try {
        $captureResponse = captureOrder($orderID);
        echo json_encode($captureResponse["jsonResponse"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }
}

?>
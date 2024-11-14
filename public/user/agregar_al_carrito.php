<?php
session_start();
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';


// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true || !isset($_SESSION['email'])) {
    echo "Debes iniciar sesión para añadir productos al carrito.";
    exit();
}

// Obtener el usuario_id usando el email almacenado en la sesión
$email = $_SESSION['email'];
$sql_user_id = "SELECT id FROM autenticacion WHERE email = ?";
$stmt_user_id = $mysqli->prepare($sql_user_id);
$stmt_user_id->bind_param('s', $email);
$stmt_user_id->execute();
$result_user_id = $stmt_user_id->get_result();

if ($result_user_id->num_rows == 0) {
    echo "Usuario no encontrado.";
    exit();
}

$user = $result_user_id->fetch_assoc();
$usuario_id = $user['id'];  // Ahora tienes el usuario_id

// Verificación para asegurarse de que $usuario_id no sea NULL
if (empty($usuario_id)) {
    echo "El usuario no tiene un ID válido.";
    exit();
}

// Obtener el ID del producto y la cantidad desde la URL
$productId = isset($_GET['id']) ? $_GET['id'] : null;
$quantity = isset($_GET['cantidad']) ? $_GET['cantidad'] : 1;  // Establecer 1 como valor predeterminado si no se pasa la cantidad

if ($productId && $quantity) {
    // Asegurarse de que el producto existe
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Comprobar si el usuario ya tiene un carrito
        $sql_check_cart = "SELECT * FROM carrito WHERE usuario_id = ?";
        $stmt_check_cart = $mysqli->prepare($sql_check_cart);
        $stmt_check_cart->bind_param('i', $usuario_id);  
        $stmt_check_cart->execute();
        $result_check_cart = $stmt_check_cart->get_result();

        if ($result_check_cart->num_rows > 0) {
            $cart = $result_check_cart->fetch_assoc();
            $cartId = $cart['id'];
        } else {
            $sql_insert_cart = "INSERT INTO carrito (usuario_id) VALUES (?)";
            $stmt_insert_cart = $mysqli->prepare($sql_insert_cart);
            $stmt_insert_cart->bind_param('i', $usuario_id);
            $stmt_insert_cart->execute();
            $cartId = $stmt_insert_cart->insert_id;
        }

        // Comprobar si el producto ya está en el carrito
        $sql_check_item = "SELECT * FROM carrito_item WHERE carrito_id = ? AND producto_id = ?";
        $stmt_check_item = $mysqli->prepare($sql_check_item);
        $stmt_check_item->bind_param('ii', $cartId, $productId);
        $stmt_check_item->execute();
        $result_check_item = $stmt_check_item->get_result();

        if ($result_check_item->num_rows > 0) {
            // El producto ya está en el carrito, actualizar la cantidad
            $carrito_item = $result_check_item->fetch_assoc();
            $cantidad_existente = $carrito_item['cantidad'];
            $cantidad_nueva = $cantidad_existente + $quantity;

            if ($cantidad_nueva > $product['cantidad']) {
                // Si la suma sobrepasa el stock, limitar la cantidad al stock disponible
                $cantidad_nueva = $product['cantidad'];
            }

            $stmt_update_item = $mysqli->prepare("UPDATE carrito_item SET cantidad = ? WHERE carrito_id = ? AND producto_id = ?");
            $stmt_update_item->bind_param('iii', $cantidad_nueva, $cartId, $productId);
            $stmt_update_item->execute();

        } else {
            // El producto no está en el carrito, añadirlo como nuevo item si hay suficiente stock
            if ($quantity > $product['cantidad']) {
                $quantity = $product['cantidad'];  // Limitar cantidad al stock disponible
            }
            $stmt_insert_item = $mysqli->prepare("INSERT INTO carrito_item (carrito_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
            $stmt_insert_item->bind_param('iiid', $cartId, $productId, $quantity, $product['precio']);
            $stmt_insert_item->execute();
        }

        echo "Producto añadido al carrito con éxito.";
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "Datos inválidos.";
}

$mysqli->close();
?>

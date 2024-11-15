<?php
session_start();
require __DIR__ . '/../config/conexion.php';

function log_message($message) {
    $logFile = __DIR__ . '/../private/log.txt';
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message . "\n", 3, $logFile);
}

// Verificar autenticación y rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php');
    exit();
}

log_message("Iniciando crear_producto.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $genero = $_POST['genero'];
    $categoria_id = $_POST['categoria_id'];

    $target_dir = __DIR__ . "/../uploads/";
    $imagen = $_FILES["imagen"]["name"];
    $target_file = $target_dir . basename($imagen);
    
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $email = $_SESSION['email'];
        $sql_user_id = "SELECT id FROM autenticacion WHERE email = ?";
        
        if ($stmt = $mysqli->prepare($sql_user_id)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($usuario_id);
            $stmt->fetch();
            $stmt->close();
            
            $sql = "INSERT INTO productos (usuario_id, nombre, descripcion, precio, cantidad, imagen, genero, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("issdissi", $usuario_id, $nombre, $descripcion, $precio, $cantidad, $imagen, $genero, $categoria_id);
                $stmt->execute();
                log_message("Producto creado con éxito.");
                header('Location: ver_stock.php');
            } else {
                log_message("Error al insertar producto: " . $mysqli->error);
            }
        }
    } else {
        log_message("Error al subir imagen.");
    }
    $mysqli->close();
}
?>

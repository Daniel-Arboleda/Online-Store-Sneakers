<?php
session_start();
require __DIR__ . '/../config/conexion.php';

function log_message($message) {
    $logFile = __DIR__ . '/../private/log.txt';
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message . "\n", 3, $logFile);
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php');
    exit();
}

log_message("Iniciando actualizar_producto.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $genero = $_POST['genero'];
    $categoria_id = $_POST['categoria_id'];
    $imagen = $_FILES["imagen"]["name"];

    $target_dir = __DIR__ . "/../uploads/";
    $target_file = $target_dir . basename($imagen);

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ?, genero = ?, categoria_id = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssdisssi", $nombre, $descripcion, $precio, $cantidad, $imagen, $genero, $categoria_id, $id_producto);
            $stmt->execute();
            log_message("Producto actualizado con éxito.");
            header('Location: ver_stock.php');
        } else {
            log_message("Error al actualizar producto: " . $mysqli->error);
        }
    } else {
        log_message("Error al subir imagen.");
    }
    $mysqli->close();
}
?>

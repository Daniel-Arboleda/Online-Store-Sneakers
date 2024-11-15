<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/../config/conexion.php';

// Función para registrar logs
function log_message($message) {
    $logFile = __DIR__ . '/../private/log.txt';
    if (file_exists($logFile) && is_writable($logFile)) {
        error_log("[" . date('Y-m-d H:i:s') . "] " . $message . "\n", 3, $logFile);
    } else {
        error_log("[" . date('Y-m-d H:i:s') . "] Error: No se puede escribir en el archivo de log ($logFile)\n", 3, $logFile);
    }
}

log_message("Iniciando guardar_producto.php");

// Obtener datos del producto desde el POST
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];
$genero_id = $_POST['genero_id'];
$categoria_id = $_POST['categoria_id'];
$id = $_POST['id'] ?? null;

// Directorio para guardar la imagen
$target_dir = __DIR__ . "/../uploads/";
$target_file = $target_dir . basename($_FILES["imagen"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validación y carga de la imagen
if ($_FILES["imagen"]["size"] > 5000000) {
    log_message("Error: Archivo demasiado grande.");
    $uploadOk = 0;
}

if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
    log_message("Error: Tipo de archivo no permitido.");
    $uploadOk = 0;
}

if ($uploadOk && !move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
    log_message("Error al mover el archivo de imagen.");
    echo "Error al subir la imagen.";
    exit();
}

// Obtener el ID del usuario desde la sesión
$email = $_SESSION['email'];
$sql_user_id = "SELECT id FROM autenticacion WHERE email = ?";
if ($stmt = $mysqli->prepare($sql_user_id)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();

    if ($usuario_id) {
        // Si el producto tiene ID, actualizarlo; si no, crearlo
        if ($id) {
            $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ?, genero_id = ?, categoria_id = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssdissii", $nombre, $descripcion, $precio, $cantidad, basename($target_file), $genero_id, $categoria_id, $id);
                log_message("Actualizando producto ID: " . $id);
            }
        } else {
            $sql = "INSERT INTO productos (usuario_id, nombre, descripcion, precio, cantidad, imagen, genero_id, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("issdissi", $usuario_id, $nombre, $descripcion, $precio, $cantidad, basename($target_file), $genero_id, $categoria_id);
                log_message("Insertando nuevo producto");
            }
            
        }

        if ($stmt->execute()) {
            log_message("Producto guardado exitosamente.");
            header('Location: ver_stock.php');
        } else {
            log_message("Error al guardar producto en la DB: " . $stmt->error);
            echo "Error al guardar el producto en la base de datos: " . $stmt->error;
        }
        $stmt->close();
    } else {
        log_message("No se encontró usuario con el email proporcionado.");
        echo "No se encontró un usuario con el email proporcionado.";
    }
} else {
    log_message("Error en la consulta para obtener usuario_id: " . $mysqli->error);
    echo "Error al preparar la consulta para obtener el usuario_id: " . $mysqli->error;
}

$mysqli->close();
?>

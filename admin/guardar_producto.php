<!-- guardar_producto.php -->


<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

require __DIR__ . '/../config/conexion.php';

// Obtener un producto específico por su ID
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM productos WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        echo json_encode($producto); // Devuelve los datos del producto en formato JSON
        exit(); // Termina la ejecución aquí si es una solicitud GET
    } else {
        echo "Error al preparar la consulta SQL para obtener el producto.";
        exit();
    }
}

// Guardar o actualizar un producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $genero = $_POST['genero']; // Se añadió el campo género
    $id_producto = $_POST['id_producto'] ?? null; // Para editar un producto si el ID está presente

    // Manejar la subida de la imagen
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Comprobar si el archivo es una imagen real o una imagen falsa
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "El archivo no es una imagen.";
        $uploadOk = 0;
    }

    // Comprobar si el archivo ya existe
    if (file_exists($target_file)) {
        echo "Lo siento, el archivo ya existe.";
        $uploadOk = 0;
    }

    // Comprobar el tamaño del archivo
    if ($_FILES["imagen"]["size"] > 5000000) { // 5 MB
        echo "Lo siento, tu archivo es demasiado grande.";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    // Comprobar si $uploadOk es 0 debido a un error
    if ($uploadOk == 0) {
        echo "Lo siento, tu archivo no fue subido.";
    } else {
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            // Obtener el usuario_id basado en el email
            $sql_user_id = "SELECT id FROM autenticacion WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql_user_id)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($usuario_id);
                $stmt->fetch();
                $stmt->close();

                // Verificar que se obtuvo el usuario_id
                if ($usuario_id) {
                    if ($id_producto) {
                        // Actualizar producto existente
                        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, imagen = ?, genero = ? WHERE id = ?";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("ssdisss", $nombre, $descripcion, $precio, $cantidad, basename($target_file), $genero, $id_producto);
                    } else {
                        // Insertar nuevo producto
                        $sql = "INSERT INTO productos (usuario_id, nombre, descripcion, precio, cantidad, imagen, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("issdiss", $usuario_id, $nombre, $descripcion, $precio, $cantidad, basename($target_file), $genero);
                    }

                    if ($stmt->execute()) {
                        echo "El producto ha sido guardado exitosamente.";
                        // Redirigir a ver_stock.php después de la creación exitosa
                        header('Location: ver_stock.php');
                    } else {
                        echo "Error al guardar el producto en la base de datos: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "No se encontró un usuario con el email proporcionado.";
                }
            } else {
                echo "Error al preparar la consulta para obtener el usuario_id: " . $mysqli->error;
            }
        } else {
            echo "Error al subir la imagen.";
        }
    }
    $mysqli->close();
}
?>

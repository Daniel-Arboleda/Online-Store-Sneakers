<?php
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

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
    if ($_FILES["imagen"]["size"] > 5000000) { // 5 MB, 2 MB, 500 KB de límite
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
    // Intentar subir el archivo si no hubo errores
    } else {
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            // Guardar los datos del producto en la base de datos
            $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $cantidad, $target_file);
                if ($stmt->execute()) {
                    echo "El producto ha sido creado exitosamente.";
                    // Redirigir a ver_stock.php después de la creación exitosa
                    header('Location: ver_stock.php');
                } else {
                    echo "Error al guardar el producto en la base de datos: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta SQL: " . $mysqli->error;
            }

        } else {
            echo "Error al subir la imagen.";
        }
    }
    $mysqli->close();
}
?>

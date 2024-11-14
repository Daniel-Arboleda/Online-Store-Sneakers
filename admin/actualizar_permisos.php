// actualizar_permisos.php

<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

include '../config/db_connect.php';

$user_id = $_POST['user_id'];
$new_role_id = $_POST['rol_id'];

// Actualizar el rol del usuario
$update_query = "UPDATE usuarios SET rol_id = ? WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("ii", $new_role_id, $user_id);

if ($stmt->execute()) {
    echo "Permisos actualizados exitosamente";
} else {
    echo "Error al actualizar permisos";
}

header("Location: permisos.php");
exit();
?>

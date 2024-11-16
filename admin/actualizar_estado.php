<!-- actualizar_estado.php -->



<?php
session_start();
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php'); // Redirige al formulario de login si no está autenticado o no es administrador
    exit();
}

// Incluir la conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '/../config/conexion.php';

$id_pedido = $_GET['id'];
$estado = $_GET['estado'];

// Actualizar el estado del pedido
$sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $estado, $id_pedido);
$stmt->execute();

header('Location: admin_pedidos.php');
exit();

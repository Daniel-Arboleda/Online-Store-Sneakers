// editar_permisos.php

<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Obtener ID de usuario y conectar a la base de datos
$user_id = $_GET['user_id'];
include '../config/db_connect.php';

// Obtener roles y permisos actuales del usuario
$roles_query = "SELECT id, nombre_rol FROM roles";
$roles_result = $conn->query($roles_query);

$user_role_query = "SELECT rol_id FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($user_role_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_role_result = $stmt->get_result();
$current_role = $user_role_result->fetch_assoc()['rol_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Permisos</title>
</head>
<body>
    <h2>Editar Permisos para el Usuario <?php echo $user_id; ?></h2>
    <form action="actualizar_permisos.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <label for="rol">Rol:</label>
        <select name="rol_id" id="rol">
            <?php while ($row = $roles_result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $current_role) ? 'selected' : ''; ?>>
                    <?php echo $row['nombre_rol']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit" class="btn btn-success">Actualizar Permisos</button>
    </form>
</body>
</html>

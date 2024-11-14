// formulario_crear_admin.php

<?php
session_start();

// Verificar si el usuario está logueado y si tiene rol de 'admin'
if (!isset($_SESSION['logged_in']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");  // Redirigir a login si no es admin
    exit();
}

// Aquí iría el formulario para crear nuevos usuarios admin
?>
<form action="crear_admin_backend.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="submit" value="Crear Administrador">
</form>

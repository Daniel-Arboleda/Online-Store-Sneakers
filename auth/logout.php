<!-- /auth/logout. -->
<?php
session_start(); // Iniciar la sesión

// Cerrar la sesión
session_unset();
session_destroy();

// Redirigir al formulario de login
header('Location: ../public/index.php');
exit();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "storethays";

// Crear conexión
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

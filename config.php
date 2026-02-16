<?php
$host = "localhost";
$db = "sistema_web";
$user = "sistema_user";
$pass = "password_seguro";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

session_start();
?>

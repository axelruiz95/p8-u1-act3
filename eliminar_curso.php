<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM cursos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: cursos.php");

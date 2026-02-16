<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole('estudiante');

$curso_id = (int)($_GET['curso_id'] ?? 0);
if (!$curso_id) { header("Location: cursos.php"); exit(); }

$uid = (int)$_SESSION['usuario']['id'];
$existe = $conn->query("SELECT 1 FROM inscripciones WHERE estudiante_id = $uid AND curso_id = $curso_id")->fetch_assoc();
if (!$existe) {
    $stmt = $conn->prepare("INSERT INTO inscripciones (estudiante_id, curso_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $uid, $curso_id);
    $stmt->execute();
}
header("Location: curso_detalle.php?id=$curso_id");
exit();

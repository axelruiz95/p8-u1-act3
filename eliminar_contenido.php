<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

$id = (int)($_GET['id'] ?? 0);
$curso_id = (int)($_GET['curso_id'] ?? 0);
if (!$id) { header("Location: cursos.php"); exit(); }

$stmt = $conn->prepare("DELETE FROM contenidos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: curso_detalle.php?id=" . ($curso_id ?: 0) . "&tab=contenidos");
exit();

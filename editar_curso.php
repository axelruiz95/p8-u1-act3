<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: cursos.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    if ($titulo !== '') {
        $stmt = $conn->prepare("UPDATE cursos SET titulo=?, descripcion=? WHERE id=?");
        $stmt->bind_param("ssi", $titulo, $descripcion, $id);
        $stmt->execute();
        header("Location: curso_detalle.php?id=$id");
        exit();
    }
}

$stmt = $conn->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$curso = $stmt->get_result()->fetch_assoc();
if (!$curso) { header("Location: cursos.php"); exit(); }

$pageTitle = 'Editar curso - Sistema Web + Multimedia';
include 'header.php';
?>
<main class="container">
    <h2>Editar curso</h2>
    <form method="POST" class="card form-card">
        <label>Título <input type="text" name="titulo" value="<?= htmlspecialchars($curso['titulo']) ?>" required></label>
        <label>Descripción <textarea name="descripcion"><?= htmlspecialchars($curso['descripcion'] ?? '') ?></textarea></label>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
    <p><a href="curso_detalle.php?id=<?= $id ?>" class="btn btn-secondary">Volver al curso</a></p>
</main>
<?php include 'footer.php'; ?>

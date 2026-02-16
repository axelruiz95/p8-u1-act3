<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

$pageTitle = 'Crear curso - Sistema Web + Multimedia';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $docente_id = (int)$_SESSION['usuario']['id'];
    if ($titulo !== '') {
        $stmt = $conn->prepare("INSERT INTO cursos (titulo, descripcion, docente_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titulo, $descripcion, $docente_id);
        $stmt->execute();
        header("Location: cursos.php");
        exit();
    }
}
include 'header.php';
?>
<main class="container">
    <h2>Crear curso</h2>
    <form method="POST" class="card form-card" id="formCrearCurso">
        <label>Título <input type="text" name="titulo" required></label>
        <label>Descripción <textarea name="descripcion"></textarea></label>
        <button type="submit" class="btn btn-primary">Crear curso</button>
    </form>
    <p><a href="cursos.php" class="btn btn-secondary">Volver a cursos</a></p>
</main>
<?php include 'footer.php'; ?>

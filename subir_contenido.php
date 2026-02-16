<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $curso_id = (int)($_POST['curso_id'] ?? 0);
    if (!$titulo || !$curso_id) {
        $error = "Título y curso son obligatorios.";
    } elseif (!empty($_FILES['archivo']['name'])) {
        $archivo = $_FILES['archivo'];
        $nombreArchivo = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $archivo['name']);
        $rutaDestino = __DIR__ . "/uploads/" . $nombreArchivo;
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            $tipo = $archivo['type'];
            $url = "uploads/" . $nombreArchivo;
            $stmt = $conn->prepare("INSERT INTO contenidos (titulo, tipo, url, curso_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $titulo, $tipo, $url, $curso_id);
            $stmt->execute();
            header("Location: curso_detalle.php?id=" . $curso_id);
            exit();
        }
        $error = "Error al subir el archivo.";
    } else {
        $error = "Seleccione un archivo.";
    }
}
include 'header.php';
$cursos = $conn->query("SELECT id, titulo FROM cursos ORDER BY titulo");
?>
<main class="container">
    <h2>Subir contenido multimedia</h2>
    <?php if (!empty($error)): ?><p class="alert alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="card form-card" id="formContenido">
        <label>Título <input type="text" name="titulo" required></label>
        <label>Curso
            <select name="curso_id" required>
                <option value="">Seleccione un curso</option>
                <?php while ($r = $cursos->fetch_assoc()): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['titulo']) ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>Archivo (imagen/video/audio/documento) <input type="file" name="archivo" accept="image/*,video/*,audio/*,.pdf,.doc,.docx" required></label>
        <button type="submit" class="btn btn-primary">Subir</button>
    </form>
</main>
<?php include 'footer.php'; ?>

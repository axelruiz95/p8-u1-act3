<?php
include 'config.php';
include 'auth.php';
include 'role.php';
requireRole(['docente', 'admin']);

$pageTitle = 'Crear evaluación - Sistema Web + Multimedia';
$cursos = $conn->query("SELECT id, titulo FROM cursos ORDER BY titulo");
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $curso_id = (int)($_POST['curso_id'] ?? 0);
    $enunciado = trim($_POST['enunciado'] ?? '');
    $opciones = trim($_POST['opciones'] ?? '');
    if (!$titulo || !$curso_id) { $error = 'Título y curso son obligatorios.'; }
    else {
        $docente_id = (int)$_SESSION['usuario']['id'];
        $stmt = $conn->prepare("INSERT INTO evaluaciones (titulo, curso_id, docente_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $titulo, $curso_id, $docente_id);
        $stmt->execute();
        $eid = $conn->insert_id;
        if ($eid && $enunciado !== '') {
            $opcionesArr = array_filter(array_map('trim', explode("\n", $opciones)));
            $stmt = $conn->prepare("INSERT INTO preguntas (evaluacion_id, enunciado, tipo, opciones) VALUES (?, ?, 'opcion_multiple', ?)");
            $jsonOpt = json_encode($opcionesArr);
            $stmt->bind_param("iss", $eid, $enunciado, $jsonOpt);
            $stmt->execute();
        }
        header("Location: curso_detalle.php?id=$curso_id&tab=evaluaciones");
        exit();
    }
}
include 'header.php';
?>
<main class="container">
    <h2>Crear evaluación</h2>
    <?php if ($error): ?><p class="alert alert-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST" class="card form-card" id="formCrearEval">
        <label>Título <input type="text" name="titulo" required></label>
        <label>Curso
            <select name="curso_id" required>
                <option value="">Seleccione curso</option>
                <?php while ($r = $cursos->fetch_assoc()): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['titulo']) ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>Primera pregunta (enunciado) <textarea name="enunciado" rows="2" placeholder="Texto de la pregunta"></textarea></label>
        <label>Opciones (una por línea) <textarea name="opciones" rows="4" placeholder="Opción A&#10;Opción B&#10;Opción C"></textarea></label>
        <button type="submit" class="btn btn-primary">Crear evaluación</button>
    </form>
</main>
<?php include 'footer.php'; ?>

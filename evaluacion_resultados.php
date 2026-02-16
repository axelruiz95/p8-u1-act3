<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$eid = (int)($_GET['eid'] ?? 0);
if (!$eid) { header("Location: evaluaciones.php"); exit(); }

$ev = $conn->query("SELECT e.*, c.titulo AS curso_titulo FROM evaluaciones e INNER JOIN cursos c ON c.id = e.curso_id WHERE e.id = $eid")->fetch_assoc();
if (!$ev) { header("Location: evaluaciones.php"); exit(); }

$uid = (int)$_SESSION['usuario']['id'];
$rol = $_SESSION['usuario']['rol'];
$esEstudiante = ($rol === 'estudiante');
if ($esEstudiante) {
    $res = $conn->query("SELECT * FROM resultados WHERE evaluacion_id = $eid AND estudiante_id = $uid")->fetch_assoc();
    if (!$res) { header("Location: evaluaciones.php"); exit(); }
} else {
    $resultados = [];
    $r = $conn->query("SELECT r.*, u.nombre FROM resultados r INNER JOIN usuarios u ON u.id = r.estudiante_id WHERE r.evaluacion_id = $eid");
    if ($r) $resultados = $r->fetch_all(MYSQLI_ASSOC);
}

$pageTitle = 'Resultados: ' . $ev['titulo'];
$enviado = !empty($_GET['enviado']);
if ($enviado && isset($_SESSION['evaluacion_enviada'])) unset($_SESSION['evaluacion_enviada']);
?>

<main class="container">
    <div class="card">
        <h2>Resultados: <?= htmlspecialchars($ev['titulo']) ?></h2>
        <p class="meta">Curso: <?= htmlspecialchars($ev['curso_titulo']) ?></p>
        <?php if ($enviado): ?>
            <p class="alert alert-success">Evaluación enviada correctamente.</p>
        <?php endif; ?>

        <?php if ($esEstudiante): ?>
            <p><strong>Su calificación:</strong> <?= $res['calificacion'] !== null ? $res['calificacion'] : '—' ?></p>
            <p><strong>Fecha de envío:</strong> <?= date('d/m/Y H:i', strtotime($res['enviado_en'])) ?></p>
            <a href="resultados.php" class="btn btn-secondary">Ver todos mis resultados</a>
        <?php else: ?>
            <table class="results-table">
                <thead><tr><th>Estudiante</th><th>Calificación</th><th>Enviado</th></tr></thead>
                <tbody>
                    <?php foreach ($resultados as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nombre']) ?></td>
                            <td><?= $r['calificacion'] !== null ? $r['calificacion'] : '—' ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($r['enviado_en'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($resultados)): ?><p>Aún no hay envíos.</p><?php endif; ?>
            <a href="evaluaciones.php" class="btn btn-secondary">Volver</a>
        <?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>

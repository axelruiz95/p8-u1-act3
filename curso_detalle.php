<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: cursos.php"); exit(); }

$stmt = $conn->prepare("SELECT c.*, u.nombre AS docente_nombre FROM cursos c LEFT JOIN usuarios u ON u.id = c.docente_id WHERE c.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$curso = $stmt->get_result()->fetch_assoc();
if (!$curso) { header("Location: cursos.php"); exit(); }

$tab = $_GET['tab'] ?? 'contenidos';
$pageTitle = $curso['titulo'] . ' - Sistema Web + Multimedia';

$contenidos = [];
$evals = [];
$r = $conn->query("SELECT * FROM contenidos WHERE curso_id = $id ORDER BY orden, id");
if ($r) $contenidos = $r->fetch_all(MYSQLI_ASSOC);
$r = $conn->query("SELECT * FROM evaluaciones WHERE curso_id = $id AND (activa = 1 OR activa IS NULL) ORDER BY id");
if ($r) $evals = $r->fetch_all(MYSQLI_ASSOC);
?>

<?php
$inscrito = false;
if ($_SESSION['usuario']['rol'] === 'estudiante') {
    $uid = (int)$_SESSION['usuario']['id'];
    $inscrito = (bool) $conn->query("SELECT 1 FROM inscripciones WHERE estudiante_id = $uid AND curso_id = $id")->fetch_assoc();
}
?>
<main class="container">
    <div class="card">
        <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
        <p class="meta">Docente: <?= htmlspecialchars($curso['docente_nombre'] ?? '—') ?></p>
        <?php if ($_SESSION['usuario']['rol'] === 'estudiante' && !$inscrito): ?>
            <p><a href="inscribir.php?curso_id=<?= $id ?>" class="btn btn-primary">Inscribirme a este curso</a></p>
        <?php endif; ?>

        <div class="tabs">
            <a href="?id=<?= $id ?>&tab=contenidos" class="<?= $tab === 'contenidos' ? 'active' : '' ?>">Contenidos</a>
            <a href="?id=<?= $id ?>&tab=evaluaciones" class="<?= $tab === 'evaluaciones' ? 'active' : '' ?>">Evaluaciones</a>
            <a href="?id=<?= $id ?>&tab=info" class="<?= $tab === 'info' ? 'active' : '' ?>">Información</a>
        </div>

        <?php if ($tab === 'contenidos'): ?>
            <?php
            $esDocente = in_array($_SESSION['usuario']['rol'], ['docente', 'admin']);
            if ($esDocente): ?>
                <p><a href="subir_contenido.php" class="btn btn-secondary btn-sm">Subir contenido</a></p>
            <?php endif; ?>
            <ul class="contenido-lista">
                <?php foreach ($contenidos as $cont): ?>
                    <li>
                        <span><?= htmlspecialchars($cont['titulo']) ?> (<?= htmlspecialchars($cont['tipo']) ?>)</span>
                        <span>
                            <a href="ver_contenido.php?id=<?= (int)$cont['id'] ?>" class="btn btn-primary btn-sm">Ver</a>
                            <?php if ($esDocente): ?>
                                <a href="eliminar_contenido.php?id=<?= (int)$cont['id'] ?>&curso_id=<?= $id ?>" class="btn btn-secondary btn-sm" data-confirm="¿Eliminar este contenido?">Eliminar</a>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (empty($contenidos)): ?><p>No hay contenidos en este curso.</p><?php endif; ?>

        <?php elseif ($tab === 'evaluaciones'): ?>
            <ul class="contenido-lista">
                <?php foreach ($evals as $ev): ?>
                    <li>
                        <span><?= htmlspecialchars($ev['titulo']) ?></span>
                        <span>
                            <?php if ($_SESSION['usuario']['rol'] === 'estudiante'): ?>
                                <a href="evaluacion_presentar.php?id=<?= (int)$ev['id'] ?>" class="btn btn-primary btn-sm">Presentar</a>
                            <?php else: ?>
                                <a href="evaluacion_resultados.php?eid=<?= (int)$ev['id'] ?>" class="btn btn-secondary btn-sm">Ver resultados</a>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if (in_array($_SESSION['usuario']['rol'], ['docente', 'admin'])): ?>
                <p><a href="crear_evaluacion.php" class="btn btn-secondary btn-sm">Crear evaluación</a></p>
            <?php endif; ?>
            <?php if (empty($evals)): ?><p>No hay evaluaciones en este curso.</p><?php endif; ?>

        <?php else: ?>
            <div class="info-tab">
                <p><?= nl2br(htmlspecialchars($curso['descripcion'] ?? 'Sin descripción.')) ?></p>
                <?php if (in_array($_SESSION['usuario']['rol'], ['docente', 'admin'])): ?>
                    <p>
                        <a href="editar_curso.php?id=<?= $id ?>" class="btn btn-secondary btn-sm">Editar curso</a>
                        <a href="eliminar_curso.php?id=<?= $id ?>" class="btn btn-secondary btn-sm" data-confirm="¿Eliminar este curso y todo su contenido?">Eliminar curso</a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>

<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$eid = (int)($_GET['id'] ?? 0);
if (!$eid) { header("Location: evaluaciones.php"); exit(); }

$ev = $conn->query("SELECT * FROM evaluaciones WHERE id = $eid AND (activa = 1 OR activa IS NULL)")->fetch_assoc();
if (!$ev) { header("Location: evaluaciones.php"); exit(); }

$uid = (int)$_SESSION['usuario']['id'];
$ya = $conn->query("SELECT 1 FROM resultados WHERE evaluacion_id = $eid AND estudiante_id = $uid")->fetch_assoc();
if ($ya) { header("Location: evaluacion_resultados.php?eid=$eid"); exit(); }

$preguntas = [];
$r = $conn->query("SELECT * FROM preguntas WHERE evaluacion_id = $eid ORDER BY orden, id");
if ($r) $preguntas = $r->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Presentar: ' . $ev['titulo'];
?>

<main class="container">
    <div class="card">
        <h2><?= htmlspecialchars($ev['titulo']) ?></h2>
        <p><a href="evaluaciones.php">&larr; Volver a evaluaciones</a></p>

        <form id="formEnviarEvaluacion" method="POST" action="evaluacion_guardar.php">
            <input type="hidden" name="evaluacion_id" value="<?= $eid ?>">
            <?php foreach ($preguntas as $i => $p): ?>
                <div class="pregunta-block">
                    <p><strong>Pregunta <?= $i + 1 ?>:</strong> <?= htmlspecialchars($p['enunciado']) ?></p>
                    <?php
                    $opciones = $p['opciones'] ? (is_string($p['opciones']) ? json_decode($p['opciones'], true) : $p['opciones']) : [];
                    if ($p['tipo'] === 'opcion_multiple' && $opciones):
                        foreach ($opciones as $idx => $opt): ?>
                            <label><input type="radio" name="pregunta_<?= $p['id'] ?>" value="<?= htmlspecialchars($opt) ?>"> <?= htmlspecialchars($opt) ?></label>
                        <?php endforeach;
                    elseif ($p['tipo'] === 'verdadero_falso'): ?>
                        <label><input type="radio" name="pregunta_<?= $p['id'] ?>" value="Verdadero"> Verdadero</label>
                        <label><input type="radio" name="pregunta_<?= $p['id'] ?>" value="Falso"> Falso</label>
                    <?php else: ?>
                        <label>Respuesta: <textarea name="pregunta_<?= $p['id'] ?>" rows="3" placeholder="Escriba su respuesta"></textarea></label>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if (empty($preguntas)): ?>
                <p>No hay preguntas en esta evaluación.</p>
            <?php else: ?>
                <button type="submit" class="btn btn-primary">Enviar evaluación</button>
            <?php endif; ?>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>

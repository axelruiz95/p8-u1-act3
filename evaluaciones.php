<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$pageTitle = 'Evaluaciones - Sistema Web + Multimedia';
$uid = (int)$_SESSION['usuario']['id'];
$rol = $_SESSION['usuario']['rol'];

if ($rol === 'estudiante') {
    $sql = "SELECT e.id, e.titulo, c.titulo AS curso_titulo, 
            (SELECT COUNT(*) FROM resultados r WHERE r.evaluacion_id = e.id AND r.estudiante_id = $uid) AS ya_presentada
            FROM evaluaciones e
            INNER JOIN cursos c ON c.id = e.curso_id
            INNER JOIN inscripciones i ON i.curso_id = c.id AND i.estudiante_id = $uid
            WHERE (e.activa = 1 OR e.activa IS NULL)";
} else {
    $sql = "SELECT e.id, e.titulo, c.titulo AS curso_titulo 
            FROM evaluaciones e INNER JOIN cursos c ON c.id = e.curso_id 
            WHERE e.docente_id = $uid";
}
$result = @$conn->query($sql);
$lista = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<main class="container">
    <h2>Evaluaciones</h2>
    <div class="card">
        <ul class="contenido-lista">
            <?php foreach ($lista as $ev): ?>
                <li>
                    <span><strong><?= htmlspecialchars($ev['titulo']) ?></strong> — <?= htmlspecialchars($ev['curso_titulo'] ?? '') ?></span>
                    <span>
                        <?php if ($rol === 'estudiante'): ?>
                            <?php if (!empty($ev['ya_presentada'])): ?>
                                <span>Presentada</span>
                                <a href="evaluacion_resultados.php?eid=<?= (int)$ev['id'] ?>" class="btn btn-secondary btn-sm">Ver resultado</a>
                            <?php else: ?>
                                <a href="evaluacion_presentar.php?id=<?= (int)$ev['id'] ?>" class="btn btn-primary btn-sm">Presentar</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="evaluacion_resultados.php?eid=<?= (int)$ev['id'] ?>" class="btn btn-secondary btn-sm">Ver resultados del grupo</a>
                        <?php endif; ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (empty($lista)): ?><p>No hay evaluaciones disponibles.</p><?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>

<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$pageTitle = 'Mis resultados - Sistema Web + Multimedia';
$uid = (int)$_SESSION['usuario']['id'];
$lista = [];
$r = @$conn->query("SELECT r.*, e.titulo AS eval_titulo, c.titulo AS curso_titulo 
    FROM resultados r 
    INNER JOIN evaluaciones e ON e.id = r.evaluacion_id 
    INNER JOIN cursos c ON c.id = e.curso_id 
    WHERE r.estudiante_id = $uid ORDER BY r.enviado_en DESC");
if ($r) $lista = $r->fetch_all(MYSQLI_ASSOC);
else $lista = [];
?>

<main class="container">
    <h2>Mis resultados</h2>
    <div class="card">
        <table class="results-table">
            <thead><tr><th>Evaluación</th><th>Curso</th><th>Calificación</th><th>Fecha</th></tr></thead>
            <tbody>
                <?php foreach ($lista as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['eval_titulo']) ?></td>
                        <td><?= htmlspecialchars($row['curso_titulo']) ?></td>
                        <td><?= $row['calificacion'] !== null ? $row['calificacion'] : '—' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['enviado_en'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($lista)): ?><p>No tiene resultados registrados.</p><?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>

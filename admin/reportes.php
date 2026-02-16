<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../auth.php';
include __DIR__ . '/../role.php';
requireRole('admin');

$pageTitle = 'Reportes - Admin';
$base = '../';
include __DIR__ . '/../header.php';

$cursosActivos = 0;
$r = $conn->query("SELECT COUNT(*) as n FROM cursos WHERE activo = 1 OR activo IS NULL");
if ($r) $cursosActivos = (int)$r->fetch_assoc()['n'];

$estudiantes = 0;
$r = $conn->query("SELECT COUNT(*) as n FROM usuarios WHERE rol = 'estudiante'");
if ($r) $estudiantes = (int)$r->fetch_assoc()['n'];

$docentes = 0;
$r = $conn->query("SELECT COUNT(*) as n FROM usuarios WHERE rol = 'docente'");
if ($r) $docentes = (int)$r->fetch_assoc()['n'];

$inscripciones = 0;
$r = $conn->query("SELECT COUNT(*) as n FROM inscripciones");
if ($r) $inscripciones = (int)$r->fetch_assoc()['n'];

$promedios = [];
$r = $conn->query("SELECT e.titulo, AVG(r.calificacion) as prom FROM resultados r INNER JOIN evaluaciones e ON e.id = r.evaluacion_id GROUP BY r.evaluacion_id");
if ($r) while ($row = $r->fetch_assoc()) $promedios[] = $row;
?>
<main class="container">
    <h2>Reportes</h2>
    <div class="dashboard-cards">
        <div class="card"><h3>Cursos activos</h3><p class="lead" style="margin:0; font-size:1.5rem;"><?= $cursosActivos ?></p></div>
        <div class="card"><h3>Estudiantes</h3><p class="lead" style="margin:0; font-size:1.5rem;"><?= $estudiantes ?></p></div>
        <div class="card"><h3>Docentes</h3><p class="lead" style="margin:0; font-size:1.5rem;"><?= $docentes ?></p></div>
        <div class="card"><h3>Inscripciones</h3><p class="lead" style="margin:0; font-size:1.5rem;"><?= $inscripciones ?></p></div>
    </div>
    <div class="card">
        <h3>Promedios por evaluación</h3>
        <table class="results-table">
            <thead><tr><th>Evaluación</th><th>Promedio</th></tr></thead>
            <tbody>
                <?php foreach ($promedios as $row): ?>
                    <tr><td><?= htmlspecialchars($row['titulo']) ?></td><td><?= $row['prom'] !== null ? number_format($row['prom'], 2) : '—' ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($promedios)): ?><p>No hay promedios registrados.</p><?php endif; ?>
    </div>
    <p><a href="../dashboard.php" class="btn btn-secondary">Volver al Dashboard</a></p>
</main>
<?php include __DIR__ . '/../footer.php'; ?>

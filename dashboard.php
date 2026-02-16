<?php
include 'config.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
$rol = $_SESSION['usuario']['rol'];
$uid = (int)$_SESSION['usuario']['id'];

$pageTitle = 'Dashboard - Sistema Web + Multimedia';
include 'header.php';

// Resúmenes según rol
$misCursos = 0;
$evaluacionesPendientes = 0;
$ultimosResultados = [];

if ($rol === 'estudiante') {
    $r = @$conn->query("SELECT COUNT(*) as n FROM inscripciones WHERE estudiante_id = $uid");
    if ($r) $misCursos = (int)$r->fetch_assoc()['n'];
    $r = @$conn->query("SELECT COUNT(DISTINCT e.id) as n FROM evaluaciones e INNER JOIN inscripciones i ON i.curso_id = e.curso_id WHERE i.estudiante_id = $uid AND (e.activa = 1 OR e.activa IS NULL) AND e.id NOT IN (SELECT evaluacion_id FROM resultados WHERE estudiante_id = $uid)");
    if ($r) $evaluacionesPendientes = (int)$r->fetch_assoc()['n'];
    $r = @$conn->query("SELECT r.calificacion, r.enviado_en, ev.titulo FROM resultados r INNER JOIN evaluaciones ev ON ev.id = r.evaluacion_id WHERE r.estudiante_id = $uid ORDER BY r.enviado_en DESC LIMIT 5");
    if ($r) while ($row = $r->fetch_assoc()) $ultimosResultados[] = $row;
} elseif ($rol === 'docente') {
    $r = $conn->query("SELECT COUNT(*) as n FROM cursos WHERE docente_id = $uid");
    if ($r) $misCursos = (int)$r->fetch_assoc()['n'];
    $r = @$conn->query("SELECT COUNT(*) as n FROM evaluaciones WHERE docente_id = $uid AND (activa = 1 OR activa IS NULL)");
    if ($r) $evaluacionesPendientes = (int)$r->fetch_assoc()['n'];
} elseif ($rol === 'admin') {
    $r = $conn->query("SELECT COUNT(*) as n FROM cursos WHERE (activo = 1 OR activo IS NULL)");
    if ($r) $misCursos = (int)$r->fetch_assoc()['n'];
    $r = $conn->query("SELECT COUNT(*) as n FROM usuarios WHERE rol = 'estudiante'");
    if ($r) $evaluacionesPendientes = (int)$r->fetch_assoc()['n']; // reutilizamos para "estudiantes"
}
?>

<main class="container">
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h2>
    <p class="lead">Rol: <strong><?= ucfirst($rol) ?></strong></p>

    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <nav>
                <a href="cursos.php"><?= $rol === 'estudiante' ? 'Catálogo de cursos' : 'Mis cursos' ?></a>
                <?php if ($rol !== 'admin'): ?>
                    <a href="evaluaciones.php">Evaluaciones</a>
                    <a href="resultados.php">Resultados</a>
                <?php endif; ?>
                <?php if (in_array($rol, ['docente', 'admin'])): ?>
                    <a href="crear_curso.php">Crear curso</a>
                <?php endif; ?>
                <?php if ($rol === 'admin'): ?>
                    <a href="admin/usuarios.php">Usuarios</a>
                    <a href="admin/reportes.php">Reportes</a>
                <?php endif; ?>
                <a href="instrucciones.php">Instrucciones</a>
                <a href="logout.php">Cerrar sesión</a>
            </nav>
        </aside>
        <div class="dashboard-main">
            <div class="dashboard-cards">
                <div class="card">
                    <h3><?= $rol === 'admin' ? 'Cursos activos' : 'Mis cursos' ?></h3>
                    <p class="lead" style="margin:0; font-size:1.5rem;"><?= $misCursos ?></p>
                    <a href="cursos.php" class="btn btn-primary btn-sm">Ver</a>
                </div>
                <div class="card">
                    <h3><?= $rol === 'admin' ? 'Estudiantes' : 'Evaluaciones' ?></h3>
                    <p class="lead" style="margin:0; font-size:1.5rem;"><?= $evaluacionesPendientes ?></p>
                    <a href="<?= $rol === 'admin' ? 'admin/reportes.php' : 'evaluaciones.php' ?>" class="btn btn-secondary btn-sm">Ver</a>
                </div>
            </div>
            <?php if ($rol === 'estudiante' && count($ultimosResultados)): ?>
                <div class="card">
                    <h3>Últimos resultados</h3>
                    <table class="results-table">
                        <thead><tr><th>Evaluación</th><th>Calificación</th><th>Fecha</th></tr></thead>
                        <tbody>
                            <?php foreach ($ultimosResultados as $res): ?>
                                <tr>
                                    <td><?= htmlspecialchars($res['titulo']) ?></td>
                                    <td><?= $res['calificacion'] !== null ? $res['calificacion'] : '—' ?></td>
                                    <td><?= date('d/m/Y', strtotime($res['enviado_en'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="resultados.php" class="btn btn-secondary btn-sm">Ver todos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>

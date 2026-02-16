<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$pageTitle = 'Cursos - Sistema Web + Multimedia';
$rol = $_SESSION['usuario']['rol'];
$uid = (int)$_SESSION['usuario']['id'];
$sql = "SELECT c.id, c.titulo, c.descripcion, c.imagen_portada, u.nombre AS docente_nombre
        FROM cursos c
        LEFT JOIN usuarios u ON u.id = c.docente_id
        WHERE (c.activo = 1 OR c.activo IS NULL)";
if ($rol === 'docente') {
    $sql .= " AND c.docente_id = $uid";
} elseif ($rol === 'admin') {
    // admin ve todos
}
$result = $conn->query($sql);
$cursos = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<main class="container">
    <h2><?= $_SESSION['usuario']['rol'] === 'estudiante' ? 'Catálogo de cursos' : 'Mis cursos' ?></h2>
    <div class="search-bar">
        <input type="search" id="buscarCursos" placeholder="Buscar por título o descripción..." aria-label="Buscar cursos">
    </div>
    <div class="cursos-grid">
        <?php foreach ($cursos as $c): ?>
            <article class="curso-card" data-titulo="<?= htmlspecialchars($c['titulo']) ?>" data-desc="<?= htmlspecialchars($c['descripcion'] ?? '') ?>">
                <div class="curso-card-img">
                    <?php if (!empty($c['imagen_portada']) && file_exists($c['imagen_portada'])): ?>
                        <img src="<?= htmlspecialchars($c['imagen_portada']) ?>" alt="">
                    <?php else: ?>
                        <span aria-hidden="true">📚</span>
                    <?php endif; ?>
                </div>
                <div class="curso-card-body">
                    <h3><?= htmlspecialchars($c['titulo']) ?></h3>
                    <p class="meta"><?= htmlspecialchars($c['docente_nombre'] ?? 'Sin asignar') ?></p>
                    <p style="margin:0; font-size:0.9rem; color:var(--text-muted);"><?= htmlspecialchars(mb_substr($c['descripcion'] ?? '', 0, 80)) ?>…</p>
                    <a href="curso_detalle.php?id=<?= (int)$c['id'] ?>" class="btn btn-primary btn-sm">Ver</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (empty($cursos)): ?>
        <p class="card">No hay cursos disponibles.</p>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>

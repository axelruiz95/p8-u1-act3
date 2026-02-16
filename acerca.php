<?php
include 'config.php';
include 'header.php';
?>
<main class="container">
    <article class="card">
        <h2>Acerca del sistema</h2>
        <p>Plataforma web dinámica para la gestión de cursos, contenidos multimedia y evaluaciones. Permite a docentes crear y administrar cursos, subir materiales (imagen, video, audio) y diseñar evaluaciones; los estudiantes pueden inscribirse, ver contenidos y presentar exámenes con registro de resultados.</p>
        <p><strong>Roles:</strong> Administrador, Docente y Estudiante, con control de acceso por sesión.</p>
        <?php if (!isset($_SESSION['usuario'])): ?>
            <a href="index.php" class="btn btn-primary">Iniciar sesión</a>
        <?php endif; ?>
    </article>
</main>
<?php include 'footer.php'; ?>

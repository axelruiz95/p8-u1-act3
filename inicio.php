<?php
include 'config.php';
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}
include 'header.php';
?>
<main class="container hero">
    <h1>Sistema Web + Multimedia</h1>
    <p class="lead">Plataforma educativa para gestión de cursos, contenidos y evaluaciones.</p>
    <div class="card-grid">
        <a href="acerca.php" class="card card-link">Acerca del sistema</a>
        <a href="instrucciones.php" class="card card-link">Instrucciones de uso</a>
        <a href="index.php" class="card card-link btn-primary">Iniciar sesión</a>
    </div>
</main>
<?php include 'footer.php'; ?>

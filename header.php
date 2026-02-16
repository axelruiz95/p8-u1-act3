<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema Web + Multimedia' ?></title>
    <?php $base = isset($base) ? $base : ''; ?>
    <link rel="stylesheet" href="<?= $base ?>assets/css/styles.css">
</head>
<body>
<header class="main-header">
    <h1><a href="<?= $base ?><?= isset($_SESSION['usuario']) ? 'dashboard.php' : 'inicio.php' ?>">Sistema Web + Multimedia</a></h1>
    <nav class="main-nav">
        <?php if (isset($_SESSION['usuario'])): ?>
            <a href="<?= $base ?>dashboard.php">Dashboard</a>
            <a href="<?= $base ?>cursos.php"><?= $_SESSION['usuario']['rol'] === 'estudiante' ? 'Catálogo de cursos' : 'Cursos' ?></a>
            <?php if ($_SESSION['usuario']['rol'] !== 'admin'): ?>
                <a href="<?= $base ?>evaluaciones.php">Evaluaciones</a>
                <a href="<?= $base ?>resultados.php">Resultados</a>
            <?php endif; ?>
            <?php if (in_array($_SESSION['usuario']['rol'], ['docente', 'admin'])): ?>
                <a href="<?= $base ?>crear_curso.php">Crear curso</a>
            <?php endif; ?>
            <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                <a href="<?= $base ?>admin/usuarios.php">Usuarios</a>
                <a href="<?= $base ?>admin/reportes.php">Reportes</a>
            <?php endif; ?>
            <a href="<?= $base ?>instrucciones.php">Instrucciones</a>
            <a href="<?= $base ?>logout.php" class="nav-logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="<?= $base ?>inicio.php">Inicio</a>
            <a href="<?= $base ?>acerca.php">Acerca</a>
            <a href="<?= $base ?>instrucciones.php">Instrucciones</a>
            <a href="<?= $base ?>index.php">Iniciar sesión</a>
        <?php endif; ?>
    </nav>
</header>

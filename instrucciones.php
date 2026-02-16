<?php
include 'config.php';
include 'header.php';
?>
<main class="container">
    <article class="card">
        <h2>Instrucciones de uso</h2>
        <h3>Cómo iniciar sesión</h3>
        <p>En la página de inicio use el enlace <strong>Iniciar sesión</strong>. Ingrese su correo electrónico y contraseña y pulse <strong>Entrar</strong>. Si no tiene cuenta, contacte al administrador.</p>
        <h3>Cómo navegar los cursos</h3>
        <p>Desde el menú acceda a <strong>Cursos</strong> (o <strong>Mis cursos</strong> / <strong>Catálogo</strong> según su rol). En el listado puede buscar o filtrar. Use <strong>Ver</strong> en cada curso para abrir el detalle, donde encontrará pestañas de Contenidos, Evaluaciones e Información.</p>
        <h3>Cómo presentar evaluaciones</h3>
        <p>En el detalle del curso, vaya a la pestaña <strong>Evaluaciones</strong>, elija una evaluación disponible y pulse <strong>Presentar</strong>. Responda las preguntas y use <strong>Enviar</strong>. Recibirá una confirmación y podrá ver sus resultados en <strong>Mis resultados</strong>.</p>
        <h3>Requisitos técnicos</h3>
        <ul>
            <li>Navegador actualizado (Chrome, Firefox, Edge o Safari).</li>
            <li>JavaScript habilitado.</li>
            <li>Conexión a internet estable para contenidos multimedia.</li>
        </ul>
        <a href="<?= isset($_SESSION['usuario']) ? 'dashboard.php' : 'index.php' ?>" class="btn btn-secondary">Volver</a>
    </article>
</main>
<?php include 'footer.php'; ?>

<?php
include 'config.php';
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($correo === '' || $password === '') {
        $error = 'Correo y contraseña son obligatorios.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['usuario'] = $user;
                header("Location: dashboard.php");
                exit();
            }
            $error = 'Contraseña incorrecta.';
        } else {
            $error = 'Usuario no encontrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema Web + Multimedia</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="login-page">
    <header class="main-header">
        <h1><a href="inicio.php">Sistema Web + Multimedia</a></h1>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="instrucciones.php">Instrucciones</a>
        </nav>
    </header>
    <main class="container container-narrow">
        <div class="card login-card">
            <h2>Iniciar sesión</h2>
            <?php if ($error): ?>
                <p class="alert alert-error" role="alert"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" id="formLogin" novalidate>
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="usuario@ejemplo.edu" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                <span class="form-error" id="errorCorreo" aria-live="polite"></span>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="Contraseña">
                <span class="form-error" id="errorPassword" aria-live="polite"></span>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
            <p class="login-footer"><a href="instrucciones.php">Instrucciones de uso</a></p>
        </div>
    </main>
    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> Sistema Web + Multimedia.</p>
    </footer>
    <script src="assets/js/app.js"></script>
    <script>
    (function() {
        var form = document.getElementById('formLogin');
        var correo = document.getElementById('correo');
        var password = document.getElementById('password');
        var errorCorreo = document.getElementById('errorCorreo');
        var errorPassword = document.getElementById('errorPassword');
        var reEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function showError(el, msg) { el.textContent = msg; el.style.display = msg ? 'block' : 'none'; }

        form.addEventListener('submit', function(e) {
            var valid = true;
            showError(errorCorreo, '');
            showError(errorPassword, '');
            if (!correo.value.trim()) {
                showError(errorCorreo, 'El correo es obligatorio.');
                valid = false;
            } else if (!reEmail.test(correo.value.trim())) {
                showError(errorCorreo, 'El correo no tiene un formato válido.');
                valid = false;
            }
            if (!password.value) {
                showError(errorPassword, 'La contraseña es obligatoria.');
                valid = false;
            }
            if (!valid) e.preventDefault();
        });
    })();
    </script>
</body>
</html>

<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../auth.php';
include __DIR__ . '/../role.php';
requireRole('admin');

$pageTitle = 'Usuarios - Admin';
$base = '../';
include __DIR__ . '/../header.php';

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 'estudiante';
        if ($nombre && $correo && $password && in_array($rol, ['admin', 'docente', 'estudiante'])) {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $correo, $password, $rol);
            if ($stmt->execute()) $mensaje = 'Usuario creado.';
            else $mensaje = 'Error (¿correo duplicado?).';
        }
    } elseif ($accion === 'editar' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $nombre = trim($_POST['nombre'] ?? '');
        $rol = $_POST['rol'] ?? 'estudiante';
        if ($id && $nombre && in_array($rol, ['admin', 'docente', 'estudiante'])) {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, rol=? WHERE id=?");
            $stmt->bind_param("ssi", $nombre, $rol, $id);
            $stmt->execute();
            $mensaje = 'Usuario actualizado.';
        }
    } elseif ($accion === 'eliminar' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        if ($id && $id !== (int)$_SESSION['usuario']['id']) {
            $conn->query("DELETE FROM usuarios WHERE id = $id");
            $mensaje = 'Usuario eliminado.';
        }
    }
}

$usuarios = $conn->query("SELECT id, nombre, correo, rol FROM usuarios ORDER BY rol, nombre")->fetch_all(MYSQLI_ASSOC);
?>
<main class="container">
    <h2>Gestión de usuarios</h2>
    <?php if ($mensaje): ?><p class="alert alert-success"><?= htmlspecialchars($mensaje) ?></p><?php endif; ?>

    <div class="card form-card" style="margin-bottom:1.5rem;">
        <h3>Crear usuario</h3>
        <form method="POST">
            <input type="hidden" name="accion" value="crear">
            <label>Nombre <input type="text" name="nombre" required></label>
            <label>Correo <input type="email" name="correo" required></label>
            <label>Contraseña <input type="password" name="password" required></label>
            <label>Rol <select name="rol"><option value="estudiante">Estudiante</option><option value="docente">Docente</option><option value="admin">Admin</option></select></label>
            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>

    <div class="card">
        <table class="results-table">
            <thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['correo']) ?></td>
                        <td><?= htmlspecialchars($u['rol']) ?></td>
                        <td>
                            <?php if ($u['id'] != $_SESSION['usuario']['id']): ?>
                                <form method="POST" style="display:inline;" data-confirm="¿Eliminar este usuario?">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-secondary btn-sm">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <span>—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p><a href="../dashboard.php" class="btn btn-secondary">Volver al Dashboard</a></p>
</main>
<?php include __DIR__ . '/../footer.php'; ?>

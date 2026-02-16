<?php
function requireRole($rolPermitido) {
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }
    $roles = is_array($rolPermitido) ? $rolPermitido : [$rolPermitido];
    if (!in_array($_SESSION['usuario']['rol'], $roles)) {
        header("Location: acceso_denegado.php");
        exit();
    }
}

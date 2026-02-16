<?php
include 'config.php';
include 'auth.php';
include 'header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: cursos.php"); exit(); }
$stmt = $conn->prepare("SELECT * FROM contenidos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$c = $stmt->get_result()->fetch_assoc();
if (!$c) { header("Location: cursos.php"); exit(); }

$pageTitle = $c['titulo'] . ' - Ver contenido';
$tipo = $c['tipo'];
$url = $c['url'];
$isVideo = preg_match('/^video\//', $tipo) || preg_match('/\.(mp4|webm|ogg)$/i', $url);
$isAudio = preg_match('/^audio\//', $tipo) || preg_match('/\.(mp3|ogg|wav)$/i', $url);
$isImage = preg_match('/^image\//', $tipo) || preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $url);
$isYoutube = (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false);
?>

<main class="container">
    <div class="card">
        <h2><?= htmlspecialchars($c['titulo']) ?></h2>
        <p><a href="curso_detalle.php?id=<?= (int)$c['curso_id'] ?>">&larr; Volver al curso</a></p>

        <div class="media-player">
            <?php if ($isYoutube): ?>
                <?php
                $embed = $url;
                if (preg_match('/youtu\.be\/([^?]+)/', $url, $m)) $embed = 'https://www.youtube.com/embed/' . $m[1];
                elseif (preg_match('/[?&]v=([^&]+)/', $url, $m)) $embed = 'https://www.youtube.com/embed/' . $m[1];
                ?>
                <iframe width="100%" height="400" src="<?= htmlspecialchars($embed) ?>" frameborder="0" allowfullscreen title="Video"></iframe>
            <?php elseif ($isVideo): ?>
                <video controls width="100%">
                    <source src="<?= htmlspecialchars($url) ?>" type="<?= htmlspecialchars($tipo) ?>">
                    Tu navegador no soporta video HTML5.
                </video>
            <?php elseif ($isAudio): ?>
                <audio controls style="width:100%;">
                    <source src="<?= htmlspecialchars($url) ?>" type="<?= htmlspecialchars($tipo) ?>">
                    Tu navegador no soporta audio HTML5.
                </audio>
            <?php elseif ($isImage): ?>
                <img src="<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars($c['titulo']) ?>" style="max-width:100%; height:auto;">
            <?php else: ?>
                <p>Recurso: <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener">Abrir archivo</a></p>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>

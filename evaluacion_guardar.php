<?php
include 'config.php';
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: evaluaciones.php"); exit(); }
$eid = (int)($_POST['evaluacion_id'] ?? 0);
$uid = (int)$_SESSION['usuario']['id'];
if (!$eid) { header("Location: evaluaciones.php"); exit(); }

$ev = $conn->query("SELECT id FROM evaluaciones WHERE id = $eid")->fetch_assoc();
if (!$ev) { header("Location: evaluaciones.php"); exit(); }
if ($conn->query("SELECT 1 FROM resultados WHERE evaluacion_id = $eid AND estudiante_id = $uid")->fetch_assoc()) {
    header("Location: evaluacion_resultados.php?eid=$eid");
    exit();
}

$preguntas = $conn->query("SELECT id FROM preguntas WHERE evaluacion_id = $eid");
$puntos = 0;
$total = 0;
while ($p = $preguntas->fetch_assoc()) {
    $pid = $p['id'];
    $resp = $_POST["pregunta_$pid"] ?? '';
    if ($resp !== '') {
        $stmt = $conn->prepare("INSERT INTO respuestas_evaluacion (evaluacion_id, estudiante_id, pregunta_id, respuesta_texto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $eid, $uid, $pid, $resp);
        $stmt->execute();
    }
    $total++;
}
$calificacion = $total > 0 ? round(($puntos / $total) * 10, 2) : null;
$stmt = $conn->prepare("INSERT INTO resultados (evaluacion_id, estudiante_id, calificacion) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $eid, $uid, $calificacion);
$stmt->execute();

$_SESSION['evaluacion_enviada'] = 1;
header("Location: evaluacion_resultados.php?eid=$eid&enviado=1");
exit();

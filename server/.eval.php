<?php
// header('Content-Type: text/plain; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
if (strlen($input) == 0) die(1);

// data récupérer du client
$data = json_decode($input);
$idExam= $data->projectName;
$mode = $data->examMode;

include '.include/config.php';
include '.include/utils.php';
// les réponse qui vont être comparer
// $requestFile='/srv/http/server/bareme.json';
$requestFile='/var/www/html/verifMario.d/'.$idExam.'/.bareme.json';
// $requestFile='https://www-info.iutv.univ-paris13.fr/verifMario.d/'.$idExam.'/.bareme.json';
$bareme = json_decode(file_get_contents($requestFile));
$content = $bareme->requests;
$tolerance = !empty($bareme->tolerance) ? $bareme->tolerance : 0; // nombre d'erreur accepté

createLogsDir("$logDir/$idExam");

$res = evaluation($data, $content, $tolerance);

writeLog($res, $data, $mode, $logFile);
writeView($res);
?>

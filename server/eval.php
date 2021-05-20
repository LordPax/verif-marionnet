<?php
// header('Content-Type: text/plain; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents('php://input');
if (strlen($input) == 0) die(1);

// data récupérer du client
$data = json_decode($input);
$idExam= $data->idExam;
$mode = $data->examMode;

// les réponse qui vont être comparer
// $requestFile='/srv/http/server/bareme.json';
$requestFile='/home/gauthier/public_html/'.$idExam.'/bareme.json';
$bareme = json_decode(file_get_contents($requestFile));
$content = $bareme->requests;
$tolerance = !empty($bareme->tolerance) ? $bareme->tolerance : 0; // nombre d'erreur accepté

include 'include/utils.php';
include 'include/config.php';

createLogsDir("$logDir/$idExam");

$res = evaluation($data, $content, $tolerance);

writeLog($res, $data, $mode, $logFile);
writeView($res);
?>

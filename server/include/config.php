<?php
// data récupérer du client
$data = json_decode($input);
$idExam= $data->idExam;
$mode = $data->examMode;
$graph = $data->graph;

// les réponse qui vont être comparer
// $requestFile='/srv/http/server/bareme.json';
$requestFile='/home/gauthier/public_html/'.$idExam.'/bareme.json';
$bareme = json_decode(file_get_contents($requestFile));
$content = $bareme->requests;
$tolerance = !empty($bareme->tolerance) ? $bareme->tolerance : 0; // nombre d'erreur accepté

$ip=$_SERVER['REMOTE_ADDR'];

$logDir='/home/gauthier/verif-marionnet/server/log/';
$normalLogFile="$logDir/$idExam/normal/$ip.log";
$examLogFile="$logDir/$idExam/exam/$ip.log";
$logFile = $mode === 1 ? $examLogFile : $normalLogFile;
?>
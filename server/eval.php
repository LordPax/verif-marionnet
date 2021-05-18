<?php
header('Content-Type: text/plain; charset=utf-8');

$input = file_get_contents('php://input');
if (strlen($input) == 0) die(1);

include 'include/utils.php';
include 'include/config.php';

createLogsDir("$logDir/$idExam");

$res = evaluation($data, $content, $tolerance, $graph);

writeLog($res, $data, $graph, $mode, $logFile);
writeView($res, $graph);
?>
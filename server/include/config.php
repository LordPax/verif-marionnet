<?php
$ip=$_SERVER['REMOTE_ADDR'];

$logDir='/home/gauthier/verif-marionnet/server/log/';
$normalLogFile="$logDir/$idExam/normal/$ip.log";
$examLogFile="$logDir/$idExam/exam/$ip.log";
$logFile = $mode === 1 ? $examLogFile : $normalLogFile;
?>
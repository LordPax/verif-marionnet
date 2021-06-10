<?php
setlocale(LC_TIME, "fr_FR.utf8");
header('Content-Type: text/html; charset=utf-8');
require 'utils.php';
require '../Views/pattern_request.php';

if (!empty($_POST['type']) && $_POST['type'] == 'req' && !empty($_POST['idReq'])) {
    $type = htmlspecialchars($_POST['type']);
    $idReq = htmlspecialchars($_POST['idReq']);

    echo request_render($idReq);
}

if (!empty($_POST['type']) && $_POST['type'] == 'res' && !empty($_POST['idReq']) && !empty($_POST['idRes'])) {
    $type = htmlspecialchars($_POST['type']);
    $idReq = htmlspecialchars($_POST['idReq']);
    $idRes = htmlspecialchars($_POST['idRes']);

    echo response_render($idReq, $idRes);
}
?>
<?php
setlocale(LC_TIME, "fr_FR.utf8");
header('Content-Type: text/html; charset=utf-8');
require 'utils.php';

if (!empty($_POST['type']) && $_POST['type'] == 'req' && !empty($_POST['idReq'])) {
    $type = htmlspecialchars($_POST['type']);
    $idReq = htmlspecialchars($_POST['idReq']);

    echo '<hr/>';
    echo request_render($idReq, 1);
}
?>
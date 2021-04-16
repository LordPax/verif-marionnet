<?php
    header('Content-Type: text/plain; charset=utf-8');
    $data = json_decode(file_get_contents('php://input'));
    
    echo $data->data[0];
?>

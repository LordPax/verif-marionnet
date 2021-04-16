<?php
    header('Content-Type: text/plain; charset=utf-8');
    // if (strlen($data) == 0) die(1) else echo 'Ok';
    $data = json_decode(file_get_contents('php://input'));
    $file = fopen('exempleTP1_requetes.txt', 'r');

    $content = [];
    while (!feof($file)) {
        $line = fgets($file, 255);
        if (preg_match('/^[^#]/', $line) == 1 && strlen($line) > 1)
            array_push($content, explode(';', $line));
    }
    # echo var_dump($content);
    
    foreach ($data->data as $k => $v) {
        echo $content[$k][0].' : '.(preg_match('/'.trim($content[$k][3]).'/', $v) == 1 ? 'Ok' : 'Not ok')."\n";
    }
?>

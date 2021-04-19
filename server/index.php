<?php
    header('Content-Type: text/plain; charset=utf-8');

    $input = file_get_contents('php://input');
    if (strlen($input) == 0) die(1);

    $data = json_decode($input);
    $save = "";

    $file = fopen('exempleTP1_requetes.txt', 'r');

    $content = [];
    while (!feof($file)) {
        $line = fgets($file, 255);
        if (preg_match('/^[^#]/', $line) == 1 && strlen($line) > 1)
            array_push($content, explode(';', $line));
    }
    fclose($file);

    $totPts = 0;
    $note = 0;
    
    foreach ($data->data as $k => $v) {
        $bareme = isset($content[$k][4]) ? (int)trim($content[$k][4]) : 1;
        $pts = preg_match('/'.trim($content[$k][3]).'/', $v) === 1 ? $bareme : 0;

        $totPts += $bareme;
        $note += $pts;

        $save .= $content[$k][0].' : '.$pts.'/'.$bareme."\n";
    }

    $note20 = round(20 * $note / $totPts, 2);
    $save .= "Your grade is $note/$totPts => $note20/20 \n";

    // file_put_contents('log/marionnet.log', $save, FILE_APPEND);

    echo $save;
?>

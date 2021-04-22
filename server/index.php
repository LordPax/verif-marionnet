<?php
    header('Content-Type: text/plain; charset=utf-8');

    $input = file_get_contents('php://input');
    if (strlen($input) == 0) die(1);

    $logFile='/home/gauthier/verif-marionnet/server/log/request.log';
    // $logFile='/home/lordpax/Documents/Programmation/Bash/verif-marionnet/server/log/request.log';
    $requestFile='/home/gauthier/public_html/exempleTP1_requetes.txt';
    // $requestFile='/home/lordpax/Documents/Programmation/Bash/verif-marionnet/server/exempleTP1_requetes.txt';

    $data = json_decode($input);
    $show = "";
    $log = $input;

    $file = fopen($requestFile, 'r');

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

        $show .= $content[$k][0].' : '.$pts.'/'.$bareme."\n";
    }

    $note20 = round(20 * $note / $totPts, 2);
    $show .= "Your grade is $note/$totPts => $note20/20 \n";

    file_put_contents($logFile, $log, FILE_APPEND);

    echo $show;
?>

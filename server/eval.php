<?php
    header('Content-Type: text/plain; charset=utf-8');

    $input = file_get_contents('php://input');
    if (strlen($input) == 0) die(1);

    $data = json_decode($input);
    $source = $data->source; 
    $mode = $data->examMode;

    $normalLogFile='/home/gauthier/verif-marionnet/server/log/normal.log';
    $examLogFile='/home/gauthier/verif-marionnet/server/log/exam.log';
    $logFile = $mode == 1 ? $examLogFile : $normalLogFile;
    $requestFile='/home/gauthier/public_html/'.$source.'/exempleTP1_requetes.txt';
    // $requestFile='/home/lordpax/Documents/Programmation/Bash/verif-marionnet/server/exempleTP1_requetes.txt';

    $show = '';
    $log = '';
    $ip=$_SERVER['REMOTE_ADDR'];
    $date=date('d/m/Y H:i:s');

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

        $show .= trim($content[$k][0]).' : '.$pts.'/'.$bareme."\n";
    }

    $note20 = round(20 * $note / $totPts, 2);
    $show .= "Your grade is $note/$totPts => $note20/20 \n";

    if ($mode == 1)
        $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20; firstName:$data->firstName; name:$data->name; idExam:$data->idExam\n";
    else
        $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20\n";

    file_put_contents($logFile, $log, FILE_APPEND);

    echo $show;
?>

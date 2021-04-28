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
    $requestFile='/home/gauthier/public_html/'.$source.'/exempleTP1_requetes.json';
    // $requestFile='/home/lordpax/Documents/Programmation/Bash/verif-marionnet/server/exempleTP1_requetes.txt';

    $show = '';
    $log = '';
    $ip=$_SERVER['REMOTE_ADDR'];
    $date=date('d/m/Y H:i:s');

    $file = file_get_contents($requestFile);
    $content = json_decode($file);

    $totPts = 0;
    $note = 0;
    $valid = 0;
    $pts = 0;
    $comment = "";
    
    foreach ($data->data as $k => $v) {
        $bareme = !empty($content[$k]->bareme) ? $content[$k]->bareme : 1;

        for ($i = 0; $valid === 0 && $i < count($content[$k]->responses); $i++) {
            $valid = preg_match('/'.$content[$k]->responses[$i]->regex.'/', $v);
            if ($valid === 1) {
                $pts = $content[$k]->responses[$i]->pts;
                $comment = $content[$k]->responses[$i]->comment;
            }
        }

        $totPts += $bareme;
        $note += $pts;
 
        $show .= $content[$k]->label." :\t ".$pts.'/'.$bareme." \t".$comment."\n";

        $pts = 0;
        $comment = "";
        $valid = 0;

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

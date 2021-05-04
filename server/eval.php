<?php
    header('Content-Type: text/plain; charset=utf-8');

    $input = file_get_contents('php://input');
    if (strlen($input) == 0) die(1);

    /**
     * fonction qui choisie la couleur en fonction du nombre de points
     * 
     * @param pts nombre de points
     * @param bareme la note max
     * @param range marge d'erreur
     * @param code determine quel code couleur utiliser
     * @param string une couleur
     */
    function chooseColor($pts, $bareme, $range = 0, $code = 0) : string {
        $col = "";

        if ($pts >= $bareme - $range) $col = $code === 0 ? "\e[32m" : "green"; // vert si tout des points
        else if ($pts <= 0 + $range) $col = $code === 0 ? "\e[31;1m" : "red"; // rouge si aucun des points
        else $col = $code == 0 ? "\e[93;1m" : "orange"; // jaune entre les 2

        return $col;
    }

    /**
     * fonction qui vérifie la validité d'une réponse à condition qu'un de ces mot-clé exist (regex, equal, default)
     * 
     * @param resp objet contenant un mot-clé (regex, equal, default) avec la réponse attendu
     * @param value reponse obtenu
     * @return int 1 ou 0 en fonction de si la reponse est valide ou non
     */
    function validResponse(object $resp, string $value) : int { 
        $valid = 0;

        if (isset($resp->regex)) // regex keyword
            $valid = preg_match('/'.$resp->regex.'/', $value);
        else if (isset($resp->equal)) // equal keyword
            $valid = $resp->equal === $value ? 1 : 0;
        else // default keyword
            $valid = 1;

        return $valid;
    }

    // function writeLog(string $content, string $file, int $mode) {
    //     if ($mode == 1)
    //         $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20; firstName:$data->firstName; name:$data->name; idExam:$data->idExam\n";
    //     else
    //         $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20\n";

    //     file_put_contents($logFile, $log, FILE_APPEND);
    // }

    $data = json_decode($input);
    $source = $data->source; 
    $mode = $data->examMode;
    $graph = $data->graph;

    $normalLogFile='/home/gauthier/verif-marionnet/server/log/normal.log';
    $examLogFile='/home/gauthier/verif-marionnet/server/log/exam.log';
    $logFile = $mode === 1 ? $examLogFile : $normalLogFile;
    $requestFile='/home/gauthier/public_html/'.$source.'/exempleTP1_requetes.json';
    // $requestFile='/srv/http/server/exempleTP1_requetes.json';

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
    $color="";
    $questionLog = "";
    
    foreach ($data->data as $k => $v) { 
        $bareme = !empty($content[$k]->bareme) ? $content[$k]->bareme : 1;

        for ($i = 0; $valid === 0 && $i < count($content[$k]->responses); $i++) {
            $valid = validResponse($content[$k]->responses[$i], $v);
            $color = chooseColor($content[$k]->responses[$i]->pts, $bareme, 0, $graph);
            
            if ($valid === 1) {
                $pts = $content[$k]->responses[$i]->pts;
                $comment = $content[$k]->responses[$i]->comment;
            }
        }

        $totPts += $bareme;
        $note += $pts;

        $questionLog .= "; ".$content[$k]->label.":$pts/$bareme";
 
        if ($graph == 0)
            $show .= $content[$k]->label." \t $color$pts/$bareme \t $comment\e[0m\n";
        else
            $show .= $content[$k]->label."; $pts/$bareme; $comment; $color\n";

        $pts = 0;
        $comment = "";
        $valid = 0;
    }

    $note20 = round(20 * $note / $totPts, 2);
    $color = chooseColor($note20, 20, 5, $graph);

    if ($graph == 0)
        $show .= "\e[1mYour grade is $note/$totPts => $color$note20/20\e[0m\n";
    else
        $show .= ";; Your grade is $note/$totPts => $note20/20; $color";

    if ($mode == 1)
        $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20; firstName:$data->firstName; name:$data->name; idExam:$data->idExam\n";
    else
        $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20\n";
    
    $log .= $questionLog;

    file_put_contents($logFile, $log, FILE_APPEND);

    echo $show;
?>

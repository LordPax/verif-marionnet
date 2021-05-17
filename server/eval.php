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


    function chooseColorWithType(string $ctype, $code = 0) : string {
        $col = "";

        if ($ctype === "good") $col = $code === 0 ? "\e[32m" : "green";
        else if ($ctype === "wrong") $col = $code === 0 ? "\e[31;1m" : "red";
        else if ($ctype === "partial") $col = $code == 0 ? "\e[93;1m" : "orange";
	else $col = $code == 0 ? "\e[34m" : "blue"; 

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

    // echo $input;
    // var_dump($data);

    $idExam= $data->idExam;
    $mode = $data->examMode;
    $graph = $data->graph;
    
    $ip=$_SERVER['REMOTE_ADDR'];

    $logDir='/home/gauthier/verif-marionnet/server/log/';
    $normalLogFile="$logDir/$idExam/normal/$ip.log";
    $examLogFile="$logDir/$idExam/exam/$ip.log";
    $logFile = $mode === 1 ? $examLogFile : $normalLogFile;
    $requestFile='/home/gauthier/public_html/'.$idExam.'/bareme.json';
    // $requestFile='/srv/http/server/exempleTP1_requetes.json';

    if (!file_exists("$logDir/$idExam")) {
        mkdir("$logDir/$idExam/");
        mkdir("$logDir/$idExam/normal/");
        mkdir("$logDir/$idExam/exam/");
    }

    $show = '';
    $log = '';
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
    $tolerance = 0; // nombre d'erreur accepté
    $nbErr = 0;
    $type = "";
    
    foreach ($data->data as $k => $v) { 
        $bareme = $content[$k]->bareme !== 0 ? $content[$k]->bareme : $content[$k]->responses[0]->pts;

        if ($nbErr <= $tolerance) {
            for ($i = 0; $valid === 0 && $i < count($content[$k]->responses); $i++) {
                $valid = validResponse($content[$k]->responses[$i], $v);
                #$color = chooseColor($content[$k]->responses[$i]->pts, $bareme, 0, $graph); 
                $color = chooseColorWithType($content[$k]->responses[$i]->type, $graph);
                if ($valid === 1) {
                    $pts = $content[$k]->responses[$i]->pts;
                    $comment = $content[$k]->responses[$i]->comment;
                    $type = $content[$k]->responses[$i]->type;
                }
            }
        }
        else {
            $pts = 0;
            $comment = "Niveau de tolérance aux erreurs dépassées (tolérance : $tolerance)";
            $type = $content[$i]->responses[0]->type;
            if (type === "info") 
                $color = $graph === 0 ? "\e[34m" : "blue";
            else
                $color = $graph === 0 ? "\e[31;1m" : "red";
        }

        if ($type !== "info") { // les pts ne sont pas compté
            $totPts += $bareme;
            $note += $pts;
        }

        if ($pts === 0) $nbErr++;

        $questionLog .= "; ".$content[$k]->label.":$pts/$bareme";
 
        if ($graph == 0)
            $show .= $content[$k]->label." \t $color$pts/$bareme \t $comment\e[0m\n";
        else
            $show .= $content[$k]->label."; $pts/$bareme; $comment; $color\n";

        $pts = 0;
        $comment = "";
        $valid = 0;
        $type = "";
    }

    $note20 = round(20 * $note / $totPts, 2);
    $color = chooseColor($note20, 20, 5, $graph);

    if ($graph == 0)
        $show .= "\e[1mYour grade is $note/$totPts => $color$note20/20\e[0m\n";
    else
        $show .= ";; Your grade is $note/$totPts => $note20/20; $color";

    
    $log = " * IP:$ip; Date:$date; Note:$note/$totPts; Note20:$note20/20; idExam:$data->idExam";
    if ($mode == 1)
        $log .= "; firstName:$data->firstName; name:$data->name";
    $log .= "$questionLog\n";


    file_put_contents($logFile, $log, FILE_APPEND);

    echo $show;
?>

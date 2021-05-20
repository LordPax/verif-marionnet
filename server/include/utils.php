<?php
/**
 * fonction qui choisie la couleur en fonction du nombre de points
 * 
 * @param pts nombre de points
 * @param bareme la note max
 * @param range marge d'erreur
 * @param string un type (good, partial, wrong, info) 
 */
function chooseType($pts, $bareme, $range = 0) : string {
    $type = "";

    if ($pts >= $bareme - $range) $type = "good"; // vert si tout des points
    else if ($pts <= 0 + $range) $type = "wrong"; // rouge si aucun des points
    else $type = "partial"; // jaune entre les 2

    return $type;
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

function createLogsDir(string $path) {
    if (!file_exists($path)) {
        mkdir("$path/");
        mkdir("$path/normal/");
        mkdir("$path/exam/");
    }
}

/**
 * @param data information donnée par le client
 * @param content contenue des réponses du fichier bareme.json
 * @param tolerance nombre d'erreurs accepté
 * @return array
 */
function evaluation(object $data, array $content, int $tolerance) {
    $valid = 0;
    $comment = "";
    $nbErr = 0;
    $type = "";

    $totPts = 0;
    $note = 0;
    $pts = 0;
    $color="";
    $questionLog = "";
    $show = [];

    foreach ($data->data as $k => $v) { 
        $bareme = $content[$k]->bareme !== 0 ? $content[$k]->bareme : $content[$k]->responses[0]->pts;
    
        if ($nbErr <= $tolerance) {
            for ($i = 0; $valid === 0 && $i < count($content[$k]->responses); $i++) {
                $valid = validResponse($content[$k]->responses[$i], $v);
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
            $type = $content[$i]->responses[0]->type === "info" ? $content[$i]->responses[0]->type : "wrong";
        }
    
        if ($type !== "info") { // les pts ne sont pas compté si égale à info
            $totPts += $bareme;
            $note += $pts;
        }
    
        if ($pts === 0) $nbErr++;
    
        $questionLog .= "; ".$content[$k]->label.":$pts/$bareme";
        $show[] = [
            "label" => $content[$k]->label,
            "pts" => "$pts/$bareme",
            "comment" => $comment,
            "type" => $type 
        ];
    
        $pts = 0;
        $comment = "";
        $valid = 0;
        $type = "";
    }

    return [
        "totPts" => $totPts,
        "note" => $note,
        "questionLog" => $questionLog,
        "show" => $show
    ];
}

/**
 * @param res résultat de la fonction evaluation
 */
function writeView(array $res) {
    $totPts = $res["totPts"];
    $note = $res["note"];
    $show = $res["show"];

    $note20 = round(20 * $note / $totPts, 2);
    $type = chooseType($note20, 20, 5);

    echo json_encode([
        "responses" => $show,
        "grade" => [
            "comment" => "Your grade is $note/$totPts => $note20/20",
            "type" => $type
        ]
    ]);
}

/**
 * @param res résultat de la fonction evaluation
 * @param data information donnée par le client
 * @param mode mode examen (0 ou 1)
 * @param logFile chemin vers le fichier log
 */
function writeLog(array $res, object $data, int $mode, string $logFile) {
    $totPts = $res["totPts"];
    $note = $res["note"];
    $questionLog = $res["questionLog"];
    $log = "";
    $date = date('d/m/Y H:i:s');

    $note20 = round(20 * $note / $totPts, 2);

    $log = " * Date:$date; Note:$note/$totPts; Note20:$note20/20; idExam:$data->idExam";
    if ($mode == 1) // en mode exam
        $log .= "; firstName:$data->firstName; name:$data->name";
    $log .= "$questionLog\n";

    file_put_contents($logFile, $log, FILE_APPEND); // écris dans le fichier de log
}
?>
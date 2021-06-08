<?php

function e(string $val):string {
    return htmlspecialchars($val);
}

function useIfExist(mixed $data, string $key = null):string {
    if ($key !== null)
        return isset($data[$key]) ? $data[$key] : '';
    else
        return isset($data) ? $data : '';
}

function select(array $data, string $key, string $comp): string {
    return useIfExist($data, $key) === $comp ? 'selected' : '';
}

/**
 * Générère des requetes dans le formalaire
 * 
 * @param data les données reçu
 * @return string rendu html des requetes
 */
function generateRequest(array $data = []):string {
    $i = 1;
    $render = '';
    do {
        $render .= '<hr/>';
        $render .= request_render($i, $data);
        $i++;
    } while (isset($data['req'.$i.'-label']));
    return $render;
}


/**
 * Générère responses dans le formalaire
 * 
 * @param data les données reçus
 * @return string rendu html des responses 
 */
function generateResponse(int $idReq, array $data = []):string {
    $i = 1;
    $render = '';
    do {
        $render .= '<hr/>';
        $render .= response_render($idReq, $i, $data);
        $i++;
    } while (isset($data['req'.$idReq.'-res'.$i.'-typeCompare']));
    return $render;
}

/**
 * Condition qui vérifie la validité d'une clée du formulaire
 * 
 * @param val clé du formulaire
 * @return bool 
 */
function condition(string $val):bool {
    return $val !== 'TP-name' && $val !== 'tolerance' && 
    !preg_match('/req[0-9]+-(label|command|bareme)/', $val) && 
    !preg_match('/req[0-9]+-res[0-9]+-(typeCompare|compare|comment|pts|type)/', $val); 
}

/**
 * Fonction qui vérifie les valeur envyer par le formulaire
 * 
 * @param data valeur envoyées par le formulaire 
 * @return string|array tableau de valeur verifié 
 */
function checkData(array $data):string|array {
    $compare = ['equal', 'regex', 'default'];
    $type = ['good', 'partial', 'wrong', 'mandatoryGood', 'mandatoryWrong'];
    unset($data['sub']);
    $result = [];

    foreach ($data as $k => $v) {
        if ($k === 'TP-name' && empty($v)) return "2 $k $v";
        else if ($k === 'tolerance' && !preg_match('/[0-9]+/', $v)) return "3 $k $v";
        else if (preg_match('/^req[0-9]+-label$/', $k) && empty($v)) return "4 $k $v";
        else if (preg_match('/^req[0-9]+-command$/', $k) && empty($v)) return "5 $k $v";
        else if (preg_match('/^req[0-9]+-bareme$/', $k) && !preg_match('/[0-9]+/', $v)) return "6 $k $v";
        else if (preg_match('/^req[0-9]+-res[0-9]+-typeCompare$/', $k) && !in_array($v, $compare)) return "7 $k $v";
        else if (preg_match('/^req[0-9]+-res[0-9]+-compare$/', $k) && empty($v)) return "8 $k $v";
        else if (preg_match('/^req[0-9]+-res[0-9]+-comment$/', $k) && empty($v)) return "9 $k $v";
        else if (preg_match('/^req[0-9]+-res[0-9]+-pts$/', $k) && !preg_match('/[0-9]+/', $v)) return "10 $k $v";
        else if (preg_match('/^req[0-9]+-res[0-9]+-type$/', $k) && !in_array($v, $type)) return "11 $k $v";
        else if (condition($k)) return "1 $k $v";

        $result[$k] = e($v);
    }

    return $result; // aucun probleme
}

/**
 * Fonction qui convertie les données reçus dans un format json
 * 
 * @param data
 * @return string json 
 */
function data2json(array $data):string {
    $json = ['tolerance' => $data['tolerance'], 'requests' => []];
    $i = 1; $j = 1;

    while (isset($data['req'.$i.'-label'])) {
        array_push($json['requests'], [
            'label' => $data['req'.$i.'-label'],
            'command' => $data['req'.$i.'-command'],
            'bareme' => $data['req'.$i.'-bareme'],
            'responses' => []
        ]);
        while (isset($data['req'.$i.'-res'.$j.'-typeCompare'])) {
            array_push($json['requests'][$i - 1]['responses'], [
                $data['req'.$i.'-res'.$j.'-typeCompare'] => $data['req'.$i.'-res'.$j.'-compare'],
                'comment' => $data['req'.$i.'-res'.$j.'-comment'],
                'pts' => $data['req'.$i.'-res'.$j.'-pts'],
                'type' => $data['req'.$i.'-res'.$j.'-type']
            ]);
            $j++;
        }
        $i++;
        $j = 1;
    }

    return json_encode($json);
}

function searchCompare(object $elem):array {
    if (isset($elem->equal)) return ['equal', $elem->equal];
    else if (isset($elem->regex)) return ['regex', $elem->regex];
    else return ['default', $elem->default];
}

/**
 * Fait exactement le contraire de la fonction data2json
 */
function json2data(string $data):array {
    $json = json_decode($data);
    $result = ['tolerance' => $json->tolerance];
    $i = 1; $j = 1;
    $compare = null;

    foreach ($json->requests as $vReq) {
        $result['req'.$i.'-label'] = $vReq->label;
        $result['req'.$i.'-command'] = $vReq->command;
        $result['req'.$i.'-bareme'] = $vReq->bareme;
        foreach ($vReq->responses as $vRes) {
            $compare = searchCompare($vRes);
            $result['req'.$i.'-res'.$j.'-typeCompare'] = $compare[0];
            $result['req'.$i.'-res'.$j.'-compare'] = $compare[1];
            $result['req'.$i.'-res'.$j.'-comment'] = $vRes->comment;
            $result['req'.$i.'-res'.$j.'-pts'] = $vRes->pts;
            $result['req'.$i.'-res'.$j.'-type'] = $vRes->type;
            $j++;
        }
        $i++;
        $j = 1;
    }

    return $result;
}

/**
 * Fonction qui extrait les propriétés label et command du fichier .bareme.json
 */
function extractRequestFromJson(string $data):string {
    $json = json_decode($data);
    $result = [];

    foreach($json->requests as $k => $v) {
        array_push($result, [
            'label' => $v->label,
            'command' => $v->command
        ]);
    }

    return json_encode($result);
}

?>
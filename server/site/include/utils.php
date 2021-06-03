<?php

function e(string $val):string {
    return htmlspecialchars($val);
}

function useIfExist(array $data, string $key):string {
    return isset($data[$key]) ? $data[$key] : '';
}

function select(array $data, string $key, string $comp): string {
    return useIfExist($data, $key) === $comp ? 'selected' : '';
}

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

function condition(string $val):bool {
    return $val !== 'TP-name' && $val !== 'tolerance' && 
    !preg_match('/req[0-9]+-(label|command|bareme)/', $val) && 
    !preg_match('/req[0-9]+-res[0-9]+-(typeCompare|compare|comment|pts|type)/', $val); 
}

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

?>
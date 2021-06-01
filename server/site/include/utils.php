<?php
function request_render(int $idReq, int $idRes) {
    return '<div class="section" data-idreq="'.$idReq.'">
    <div class="section-header">
        <span class="section-name">Requete '.$idReq.'</span>
        <button class="more-btn">V</button>
    </div>
    <div class="section-content">
        <div class="field">
            <!-- <label for="label">label</label> -->
            <input type="text" name="req'.$idReq.'-label" id="label" class="txt-field" placeholder = "label">
        </div>
        <div class="field">
            <!-- <label for="command">command</label> -->
            <input type="text" name="req'.$idReq.'-command" id="command" class="txt-field" placeholder = "commande">
        </div>
        <div class="field">
            <input type="number" name="req'.$idReq.'-bareme" id="bareme" class="txt-field" placeholder = "bareme">
        </div>
        <div class="section">
            <div class="section-header">
                <span class="section-name">Réponses</span>
                <button class="add-btn add-response">ajouter réponse</button>
            </div>
            <div class="section-content indent">
                '.response_render($idReq, $idRes).'
            </div>
        </div>               
    </div>
</div>';
}

function response_render(int $idReq, int $idRes) {
    return '<div class="section" data-idres="'.$idRes.'">
    <div class="section-header">
        <span class="section-name">Réponse '.$idRes.'</span>
        <button class="more-btn">V</button>
    </div>
    <div class="section-content">
        <div class="field">
            <!-- <label for="compare">compare</label> -->
            <div class="combined-field">
                <select name="req'.$idReq.'-res'.$idRes.'-typeCompare" id="typeCompare">
                    <option value="equal">equal</option>
                    <option value="regex">regex</option>
                    <option value="default">default</option>
                </select>
                <input type="text" name="req'.$idReq.'-res'.$idRes.'-compare" id="compare" class="txt-field combined" placeholder="compare">
            </div>
        </div>
        <div class="field">
            <!-- <label for="comment">comment</label> -->
            <input type="text" name="req'.$idReq.'-res'.$idRes.'-comment" id="comment" class="txt-field" placeholder = "commentaire">
        </div>
        <div class="field">
            <!-- <label for="pts">pts</label> -->
            <input type="number" name="req'.$idReq.'-res'.$idRes.'-pts" id="pts" class="txt-field" placeholder = "points">
        </div>
        <div class="field">
            <label for="type">type</label>
            <select name="type" id="req'.$idReq.'-res'.$idRes.'-type">
                <option value="good">good</option>
                <option value="partial">partial</option>
                <option value="wrong">wrong</option>
                <option value="mandatoryGood">mandatoryGood</option>
                <option value="mandatoryWrong">mandatoryWrong</option>
            </select>
        </div>
    </div>
</div>';
}

// function condition($val) {
//     return $k === 'TP-name' && $k === 'tolerance' && preg_match('/req[0-9]+-label/', $k)  
// }

function checkData(array $data) {
    $compare = ['equal', 'regex', 'default'];
    $type = ['good', 'partial', 'wrong', 'mandatoryGood', 'mandatoryWrong'];
    foreach ($data as $k => $v) {
        if ($k === 'TP-name' && empty($v)) return 2;
        else if ($k === 'tolerance' && !preg_match('/[0-9]+/', $v)) return 3;
        else if (preg_match('/^req[0-9]+-label$/', $k) && empty($v)) return 4;
        else if (preg_match('/^req[0-9]+-command$/', $k) && empty($v)) return 5;
        else if (preg_match('/^req[0-9]+-bareme$/', $k) && !preg_match('/[0-9]+/', $v)) return 6;
        else if (preg_match('/^req[0-9]+-res[0-9]+-typeCompare$/', $k) && !in_array($v, $compare)) return 7;
        else if (preg_match('/^req[0-9]+-res[0-9]+-compare$/', $k) && empty($v)) return 8;
        else if (preg_match('/^req[0-9]+-res[0-9]+-comment$/', $k) && empty($v)) return 9;
        else if (preg_match('/^req[0-9]+-res[0-9]+-pts$/', $k) && empty($v)) return 10;
        else if (preg_match('/^req[0-9]+-res[0-9]+-type$/', $k) && !in_array($v, $type)) return 11;
    }
    return 0; // aucun probleme
}

function parseData(array $data) {

}
?>
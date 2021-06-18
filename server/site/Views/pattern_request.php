<?php
function request_render(int $idReq, array $data = []):string {
    return '<div class="section request" data-idreq="'.$idReq.'">
        <div class="section-header">
            <span class="section-name">Requete '.$idReq.'</span>
            <button class="suppr-btn">X</button>
            <button class="more-btn">V</button>
        </div>
        <div class="section-content">
            <div class="field">
                <label for="label">label</label>
                <input type="text" name="req'.$idReq.'-label" id="label" class="txt-field" placeholder = "label" value="'.useIfExist($data, 'req'.$idReq.'-label').'">
            </div>
            <div class="field">
                <label for="command">command</label>
                <input type="text" name="req'.$idReq.'-command" id="command" class="txt-field" placeholder = "commande" value="'.useIfExist($data, 'req'.$idReq.'-command').'">
            </div>
            <div class="field">
                <label for="bareme">bareme</label>
                <input type="text" name="req'.$idReq.'-bareme" id="bareme" class="txt-field" placeholder = "bareme (nombre)" value="'.useIfExist($data, 'req'.$idReq.'-bareme').'">
            </div>
            <div class="section">
                <div class="section-header">
                    <span class="section-name">Réponses</span>
                    <button class="add-btn add-response">ajouter réponse</button>
                </div>
                <div class="section-content indent">
                    '.generateResponse($idReq, $data).'
                </div>
            </div>               
        </div>
    </div>';
}

function response_render(int $idReq, int $idRes, array $data = []):string {
    return '<div class="section response" data-idres="'.$idRes.'">
        <div class="section-header">
            <span class="section-name">Réponse '.$idRes.'</span>
            <button class="suppr-btn">X</button>
            <button class="more-btn">V</button>
        </div>
        <div class="section-content">
            <div class="field">
                <label for="compare">compare</label>
                <div class="combined-field">
                    <select name="req'.$idReq.'-res'.$idRes.'-typeCompare" id="typeCompare">
                        <option value="equal" '.select($data, 'req'.$idReq.'-res'.$idRes.'-typeCompare', 'equal').'>equal</option>
                        <option value="regex" '.select($data, 'req'.$idReq.'-res'.$idRes.'-typeCompare', 'regex').'>regex</option>
                        <option value="default" '.select($data, 'req'.$idReq.'-res'.$idRes.'-typeCompare', 'default').'>default</option>
                    </select>
                    <input value="'.useIfExist($data, 'req'.$idReq.'-res'.$idRes.'-compare', 'compare').'" type="text" name="req'.$idReq.'-res'.$idRes.'-compare" id="compare" class="txt-field combined" placeholder="compare">
                </div>
            </div>
            <div class="field">
                <label for="comment">comment</label>
                <input value="'.useIfExist($data, 'req'.$idReq.'-res'.$idRes.'-comment').'" type="text" name="req'.$idReq.'-res'.$idRes.'-comment" id="comment" class="txt-field" placeholder = "commentaire">
            </div>
            <div class="field">
                <label for="pts">pts</label>
                <input value="'.useIfExist($data, 'req'.$idReq.'-res'.$idRes.'-pts').'" type="text" name="req'.$idReq.'-res'.$idRes.'-pts" id="pts" class="txt-field" placeholder = "points (nombre)">
            </div>
            <div class="field">
                <label for="type">type</label>
                <select name="req'.$idReq.'-res'.$idRes.'-type" id="type">
                    <option value="good" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'good').'>good</option>
                    <option value="partial" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'partial').'>partial</option>
                    <option value="wrong" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'wrong').'>wrong</option>
                    <option value="info" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'info').'>info</option>
                    <option value="mandatoryGood" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'mandatoryGood').'>mandatoryGood</option>
                    <option value="mandatoryWrong" '.select($data, 'req'.$idReq.'-res'.$idRes.'-type', 'mandatoryWrong').'>mandatoryWrong</option>
                </select>
            </div>
        </div>
    </div>';
}
?>

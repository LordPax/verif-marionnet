<?php
function request_render(int $idReq, int $idRes) {
    return '<div class="section">
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
        <div class="section">
            <div class="section-header">
                <span class="section-name">Réponses</span>
                <button class="add-btn">ajouter réponse</button>
            </div>
            <div class="section-content response">
                '.response_render($idReq, $idRes).'
            </div>
        </div>               
    </div>
</div>';
}

function response_render(int $idReq, int $idRes) {
    return '<div class="section">
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
?>
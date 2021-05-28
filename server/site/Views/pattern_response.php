<div class="section">
    <div class="section-header">
        <span class="section-name">Réponse 1</span>
        <button class="more-btn">V</button>
    </div>
    <div class="section-content">
        <div class="field">
            <!-- <label for="compare">compare</label> -->
            <div class="combined-field">
                <select name="typeCompare" id="typeCompare">
                    <option value="equal">equal</option>
                    <option value="regex">regex</option>
                    <option value="default">default</option>
                </select>
                <input type="text" name="compare" id="compare" class="txt-field combined" placeholder="compare">
            </div>
        </div>
        <div class="field">
            <!-- <label for="comment">comment</label> -->
            <input type="text" name="comment" id="comment" class="txt-field" placeholder = "commentaire">
        </div>
        <div class="field">
            <!-- <label for="pts">pts</label> -->
            <input type="number" name="pts" id="pts" class="txt-field" placeholder = "points">
        </div>
        <div class="field">
            <label for="type">type</label>
            <select name="type" id="type">
                <option value="good">good</option>
                <option value="partial">partial</option>
                <option value="wrong">wrong</option>
                <option value="mandatoryGood">mandatoryGood</option>
                <option value="mandatoryWrong">mandatoryWrong</option>
            </select>
        </div>
    </div>
</div>
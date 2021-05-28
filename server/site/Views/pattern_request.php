<div class="section">
    <div class="section-header">
        <span class="section-name">Requete 1</span>
        <button class="more-btn">V</button>
    </div>
    <div class="section-content">
        <div class="field">
            <!-- <label for="label">label</label> -->
            <input type="text" name="label" id="label" class="txt-field" placeholder = "label">
        </div>
        <div class="field">
            <!-- <label for="command">command</label> -->
            <input type="text" name="command" id="command" class="txt-field" placeholder = "commande">
        </div>
        <div class="section">
            <div class="section-header">
                <span class="section-name">Réponses</span>
                <button class="add-btn">ajouter</button>
            </div>
            <div class="section-content response">
                <?php require 'pattern_response.php'; ?>
            </div>
        </div>               
    </div>
</div>
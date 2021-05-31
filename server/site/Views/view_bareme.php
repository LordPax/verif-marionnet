<?php require 'view_begin.php';?>

<section>
    <div class="bg">
        <form action="?controller=bareme&action=edit" method="post">
            <div class="field">
                <!-- <label for="TP-name">nom du TP</label> -->
                <input type="text" name="TP-name" id="TP-name" class="txt-field" placeholder="nom du TP">
            </div>
            <div class="field">
                <!-- <label for="tolerance">tolerance</label> -->
                <input type="number" name="tolerance" id="telorance" class="txt-field" placeholder="tolerance">
            </div>
            <div class="section event">
                <div class="section-header">
                    <span class="section-name">Requetes</span>
                    <button class="add-btn add-request">ajouter requete</button>
                </div>
                <div class="section-content response">
                    <?=$request?>
                </div>
            </div>
            <input type="submit" name="sub" class="button sub-btn" value="envoyer">
        </form>
    </div>
</section>

<?php require 'view_end.php';?>
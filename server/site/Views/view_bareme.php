<?php require 'view_begin.php';?>

<section>
    <div class="bg">
        <form action="?controller=bareme&action=create" method="post" enctype="multipart/form-data">
            <div class="field">
                <!-- <label for="TP-name">nom du TP</label> -->
                <input value="<?php echo useIfExist($_POST, 'TP-name');?>"type="text" name="TP-name" id="TP-name" class="txt-field" placeholder="nom du TP">
            </div>
            <div class="field">
                <!-- <label for="tolerance">tolerance</label> -->
                <input value="<?php echo useIfExist($_POST, 'tolerance');?>" type="number" name="tolerance" id="telorance" class="txt-field" placeholder="tolerance">
            </div>
            <div class="field">
                <label for="file">upload le fichier .mar</label>
                <input type="file" name="file" id="file">
            </div>
            <div class="section event">
                <div class="section-header">
                    <span class="section-name">Requetes</span>
                    <button class="add-btn add-request">ajouter requete</button>
                </div>
                <div class="section-content indent">
                    <?=$request?>
                </div>
            </div>
            <input type="submit" name="sub" class="button sub-btn" value="envoyer">
        </form>
        <div class="msg">
            <?php if (isset($error)) { ?> 
            <div class="msg-err">
                <button class="msg-close">X</button>
                <p class="msg-content">
                    <?=$error?>
                </p>
            </div>
            <?php }?>
            <?php if (isset($ok)) { ?> 
            <div class="msg-ok">
                <button class="msg-close">X</button>
                <p class="msg-content">
                    <?=$ok?>
                </p>
            </div>
            <?php }?>
        </div>
    </div>
</section>

<?php require 'view_end.php';?>
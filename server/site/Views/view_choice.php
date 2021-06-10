<?php require 'view_begin.php';?>

<section>
    <div class="bg">
        <a href="include/disconnect.php">
            <button class="add-btn">Se déconnecter</button>
        </a>
        <a href="?controller=bareme&action=formCreate">
            <button class="sub-btn">Créer un TP</button>
        </a>
        <hr/>
        <form action="?controller=bareme&action=formEdit" method="get">
            <input type="hidden" name="controller" value="bareme">
            <input type="hidden" name="action" value="formEdit">
            <!-- <input type="text" name="tpName" class="txt-field" placeholder="Nom du TP"> -->
            <select name="tpName" id="tpName">
                <?php foreach ($projectList as $v):?>
                    <option value="<?=$v?>"><?=$v?></option>
                <?php endforeach;?>
            </select>
            <input type="submit" class="button sub-btn" value="Modifier ce TP">
        </form>
    </div>
</section>

<?php require 'view_end.php';?>

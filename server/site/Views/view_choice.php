<?php require 'view_begin.php';?>

<section>
    <div class="bg">
        <a href="?controller=bareme&action=formCreate">
            <button class="sub-btn">Créer un TP</button>
        </a>
        <hr/>
        <form action="?controller=bareme&action=formEdit" method="get">
            <input type="hidden" name="controller" value="bareme">
            <input type="hidden" name="action" value="formEdit">
            <input type="text" name="tpName" class="txt-field" placeholder="Nom du TP">
            <input type="submit" class="button sub-btn" value="Modifier ce TP">
        </form>
    </div>
</section>

<?php require 'view_end.php';?>
<?php include("view/menu.php"); ?>

<div id="editFile">
    <form method="POST">
        <textarea name="editedContent" rows = 200 cols = 80>
            <?php echo \Controller\File_Editing::findFile();?>
        </textarea>

        <input type="submit" value="Save">
    </form>

</div>


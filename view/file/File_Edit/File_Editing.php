<?php include("view/menu.php");

echo '

<div id="editFile">
    <form method="POST">
        <textarea name="editedContent" rows = 200 cols = 80>' . $data . 
             
        '</textarea>

        <input type="submit" value="Save">
    </form>

</div>
';

?>


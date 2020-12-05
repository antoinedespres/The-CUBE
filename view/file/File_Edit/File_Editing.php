<?php include("view/menu.php");

echo '

<div id="editFile">
    <form method="POST" action="/edit">
        <textarea name="editedContent" rows = 15 cols = 80>' . $data . 
             
        '</textarea>

        <input type="submit" value="Save">
    </form>

</div>
';

?>


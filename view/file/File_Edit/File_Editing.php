<?php include("view/menu.php");

echo '

<div id="editFile">
    <h1> '. $data['name'] . '</h1><br>
    <form method="POST" action="/edit">
        <textarea name="editedContent" = True rows = 15 cols = 80>' . $data['content'] . 
             
        '</textarea>

        <textarea name="name" rows = 1 cols = 8 hidden>' . $data['name'] . 
             
        '</textarea>

        <textarea name="file_id" rows = 1 cols = 8 hidden>' . $data['ID'] . 
             
        '</textarea>

        <input type="submit" value="Save">
    </form>

</div>
';

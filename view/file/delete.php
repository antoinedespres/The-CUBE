<?php 

include("view/menu.php"); 

echo '
<form method="POST">
    <fieldset>
        <legend>Delete a file</legend>
        <label for="fileName">File name: </label>
        <input type="text" name="fileName" required>
    </fieldset>
    <input type="submit" value="Delete">
</form>';

if ($data != null) echo $data;

?>

<script src="script/chat.js.php?FirstName=<?= $_SESSION['FirstName'];?>"></script>
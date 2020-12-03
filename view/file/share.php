<?php 

include("view/menu.php"); 

echo '
<form method="POST">
    <fieldset>
        <legend>Share a file to an user</legend>
        <label for="fileName">File name: </label>
        <input type="text" name="fileName" required>

        <label for="email">User e-mail: </label>
        <input type="email" name="email" required>
    </fieldset>
    <input type="submit" value="Share">
</form>';

if ($data != null) echo $data;

?>

<script src="script/chat.js.php?FirstName=<?= $_SESSION['FirstName'];?>"></script>
<?php include("view/menu.php"); ?>


<form method="POST">
    <fieldset>
        <legend>Share a file to an user</legend>
        <label for="fileName">File name: </label>
        <input type="text" name="fileName" required>

        <label for="email">User e-mail: </label>
        <input type="email" name="email" required>
    </fieldset>
    <input type="submit" value="Share">
</form>

<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<?php include("view/menu.php"); ?>

<div id="login">
	<form method="POST" action = "" name = "update">
		<fieldset>
			<legend>Choose a new password</legend>

			<label for="password1">Password: </label>
			<input type="password" name="password1" required>

            <label for="password2">Verify your password : </label>
			<input type="password" name="password2" required>
		</fieldset>
		<input type="submit" value="Reset password">
	</form>
</div>

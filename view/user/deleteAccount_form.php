<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<?php include("view/menu.php"); ?>

<div id="login">
	<form method="POST">
		<fieldset>
			<legend>ACCOUNT DELETION</legend>
			<p>Please confirm by entering your password.</p>
			<label for="password">Password : </label>
			<input type="password" name="password" required>
		</fieldset>
		<input type="submit" value="Sign In">
	</form>
</div>

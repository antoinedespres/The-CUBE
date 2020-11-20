<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<?php include("view/menu.php"); ?>

<div id="login">
	<form method="POST">
		<fieldset>
			<legend>Login</legend>
			<label for="email">Email : </label>
			<input type="email" name="email" required>

			<label for="password">Password : </label>
			<input type="password" name="password" required>
		</fieldset>
		<input type="submit" value="Sign In">
	</form>
</div>

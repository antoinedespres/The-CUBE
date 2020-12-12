<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body style="margin:0; 	background-color : #2b2142;	height : 100vh;">
	<?php include("view/menu.php"); ?>

	<div id="login">
		<form method="POST" action="/login">
			<fieldset>
				<legend>Login</legend>
				<label for="email">Email : </label>
				<input type="email" name="email" required>

				<label for="password">Password : </label>
				<input type="password" name="password" required>
			</fieldset>
			<a href="forgottenPassword">I forgot my password</a>
			<input type="submit" value="Sign In">
		</form>
	</div>
</body>

<?php 
	echo $data;
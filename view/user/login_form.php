<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body class="styleBody">
	<?php include("view/menu.php"); 

	echo' <div id="login">
		<form method="POST" action="/login">
			<fieldset>
				<legend>Login</legend>
				<label for="email">Email : </label>
				<input type="email" name="email" required>

				<label for="password">Password : </label>
				<input type="password" name="password" required>
			</fieldset>
			<a href="forgottenPassword">I forgot my password</a>
			<input type="submit" value="Sign In">'; 
		if(isset($data))
			echo '<p>' . $data . '</p>
		</form>
	</div>
</body>';


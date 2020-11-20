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
		<input type="button" value="Go to register" onclick="window.location='/index.php/register';" />  
	</form>

</div>

<style>
#login legend, label {
	font-family: sans-serif;
	font-size:13px;
}

#login form {
	width:450px;
	margin: 12% auto;
}
#login fieldset {
	background-color : #fffae9;
	border:1px solid #eedb9b;
	text-align:center;
}

#login legend {
	font-weight:700;
	text-transform:uppercase;
}

#login form input[type=submit] {
	margin : 10px 44%;
	border-radius:4px;
	padding:5px;
	background-color:#fffae9;
	border:1px solid #eedb9b;
}

#login form input[type=submit]:hover {
	background-color:#f7eac0;
}
</style>
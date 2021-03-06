<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src='../../script/requiredField.js'></script>
</head>
<body class="styleBody">
	<?php include("view/menu.php");
	echo '
	<div id="register">
		<form method="POST" action="/register">
			<fieldset>
				<legend>Registration</legend>
				<table>
				<tr>
					<td>
						<label for="firstName" class="inline requiredField">First name: </label>
						<input type="text" name="firstName" required>
					</td>
					<td>
						<label for="lastName" class="inline requiredField">Last name: </label>
						<input type="text" name="lastName"required>
					</td>
				</tr>
				<tr>
					<td>
						<label for="email" class="inline requiredField">Email: </label>
						<input type="email" name="email" required>
					</td>
					<td>
						<label for="password" class="inline requiredField">Password (8 characters min.): </label>
						<input type="password" name="password" required>
					</td>
				</tr>
				<tr>
					<td>
						<label class="requiredField" for="birthDate">Birth date (13 years old min.): </label>
						<input type="date" name="birthDate" required>
					</td>
				</tr>
				</table>
			</fieldset>
			<span>* required field</span>
			<input type="submit" value="Sign Up">
			<p>' . $data . '</p>
		</form>
	</div>
</body>';
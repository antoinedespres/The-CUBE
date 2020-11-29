<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src='../../script/requiredField.js'></script>
</head>

<?php include("view/menu.php"); ?>

<div id="register">
	<form method="POST">
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
					<label for="password" class="inline requiredField">Password: </label>
					<input type="password" name="password" required>
				</td>
			</tr>
			<tr>
				<td>
					<label for="recoveryEmail">Recovery email: </label>	
					<input type="text" name="recoveryEmail">
				</td>
				<td>
					<label for="phoneNumber">Phone number : </label>
					<input type="text" name="phoneNumber">
				</td>
			</tr>
			<tr>
				<td>
					<label class="requiredField" for="birthDate">Birth date: </label>
					<input type="date" name="birthDate" required>
				</td>
			</tr>
			</table>
		</fieldset>
		<span>* required field</span>
		<input type="submit" value="Sign Up">
	</form>
</div>
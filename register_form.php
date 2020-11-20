<div id="register">
	<form method="POST">
		<fieldset>
			<legend>Registration</legend>
			<table>
			<tr>
				<td>
					<label for="firstName" class="inline">First name * : </label>
					<input type="text" name="firstName" required>
				</td>
				<td>
					<label for="lastName" class="inline">Last name * : </label>
					<input type="text" name="lastName"required>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email" class="inline">Email * : </label>
					<input type="email" name="email" required>
				</td>
				<td>
					<label for="password" class="inline">Password * : </label>
					<input type="password" name="password" required>
				</td>
			</tr>
			<tr>
				<td>
					<label for="recoveryEmail">Recovery email : </label>	
					<input type="text" name="recoveryEmail">
				</td>
				<td>
					<label for="phoneNumber">Phone number : </label>
					<input type="text" name="phoneNumber">
				</td>
			</tr>
			<tr>
				<td>
					<label for="birthDate">Birth date * : </label>
					<input type="date" name="birthDate" required>
				</td>
			</tr>
			</table>
		</fieldset>
		<span>* required field</span>
		<input type="submit" value="Sign Up">
		<input type="button" value="Go to login" onclick="window.location='/index.php/login';" /> 
	</form>
</div>

<style>
#register legend, label, span {
	font-family: sans-serif;
	font-size:13px;
}

#register form {
	width:550px;
	margin: 12% auto;
}
#register fieldset {
	background-color : #fffae9;
	border:1px solid #eedb9b;
}
#register table {
	margin : 0 auto;
}

#register table td {
	text-align:right;
	padding-top:10px;
}
#register label .inline {
	display:inline;
}

#register legend {
	font-weight:700;
	text-transform:uppercase;

}

#register form input[type=submit] {
	margin : 10px 44%;
	border-radius:4px;
	padding:5px;
	background-color:#fffae9;
	border:1px solid #eedb9b;
}
#register form input[type=submit]:hover {
 background-color:#f7eac0;
}
</style>
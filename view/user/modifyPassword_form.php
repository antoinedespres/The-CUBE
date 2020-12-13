<head>
	<link href="./style.css" rel="stylesheet" type="text/css">
</head>

<?php include("view/menu.php");

if ($data[0] == 'change') {
	$action = '/changePassword';
	$formLabel = 'Change your password';
	$currentPasswordInput = '<label for="password0">* Current password: </label><input type="password" name="password0" required>';
} else if ($data[0] == 'reset') {
	$action = '/resetPassword';
	$formLabel = 'Create a new password';
	$currentPasswordInput = '';
}

echo '
<body class="styleBody">
	<div id="register">
		<form method="POST" action = ' . $action . ' name = "update">
			<fieldset>
				<legend>'. $formLabel  . '</legend>
				<table>
					<tr>
						<td>
						'.$currentPasswordInput.'
						</td>
					</tr>
					<tr>
						<td>
							<label for="password1">*New password: </label>
							<input type="password" name="password1" required>
						</td>
					</tr>
					<tr>
						<td>
							<label for="password2">*Verify your password: </label>
							<input type="password" name="password2" required>
						</td>
					</tr>
				</table>
			</fieldset>
			<span>* required field</span>
			<input type="submit" value="Confirm">';
			if(isset($data[1]))
				echo '<p>' . $data[1] . '</p>
		</form>
	</div>
</body>';

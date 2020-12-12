<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<?php include("view/menu.php");

if ($data == 'reset') {
	$action = 'reset';
	$formLabel = 'Create a new password';
	$originalPassword = '';
} else if ($data == 'change') {
	$action = 'change';
	$formLabel = 'Change yout password';
	$originalPassword = '<label for="password0">Current password: </label><input type="password" name="password0" required>';
}


echo '<div id="login">
	<form method="POST" action = ' . $action . ' name = "update">
		<fieldset>
			<legend>' . $label . '</legend>
			'.$originalPassword.'
			<label for="password1">New password: </label>
			<input type="password" name="password1" required>

            <label for="password2">Verify your password : </label>
			<input type="password" name="password2" required>
		</fieldset>
		<input type="submit" value="Confirm">
	</form>
</div>';

if($data != 'reset' && $data !== 'change')
	echo $data;

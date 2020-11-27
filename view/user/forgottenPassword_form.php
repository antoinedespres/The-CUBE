<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src='../../script/requiredField.js'></script>
</head>

<?php include("view/menu.php"); ?>

<div id="register">
	<form method="POST">
		<fieldset>
			<legend>Forgotten password</legend>
			<table>
			<tr>
				<td>
					<label for="email" class="inline requiredField">Email: </label>
					<input type="email" name="email" required>
				</td>
			</tr>
			</table>
		</fieldset>
		<span>* required field</span>
		<input type="submit" value="Send email">
	</form>
</div>
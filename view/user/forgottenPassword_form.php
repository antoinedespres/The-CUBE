<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src='../../script/requiredField.js'></script>
</head>

<body class="styleBody">
<?php include("view/menu.php");
echo '
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
			<input type="submit" value="Send email">';
			if (isset($data))
				echo '<p>' . $data . '</p>
		</form>
	</div>
</body>';
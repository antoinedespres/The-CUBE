<?php

include("view/menu.php");

echo '
<form method="POST" action="/upload" enctype="multipart/form-data">
	<fieldset>
		<legend>Upload</legend>

		<label for="file">File: </label>
		<input type="file" name="file" required>
	</fieldset>
	<input type="submit" value="Submit">
</form>';

if (isset($data['message']))
	echo $data['message'];

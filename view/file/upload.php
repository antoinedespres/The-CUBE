<head>
	<link href="/style.css" rel="stylesheet" type="text/css">
</head>
<?php

include("view/menu.php");

echo '
<body class="styleBody">
<div id="login">
<form method="POST" action="/uploadFile" enctype="multipart/form-data">
	<fieldset>
		<legend>Upload</legend>

		<label for="file">File: </label>
		<input type="file" name="file" required>
	</fieldset>
	<input type="submit" value="Submit">';
	if ($data != null)
	echo '<p>' .$data . '</p>
</form>
</div>
</body>';
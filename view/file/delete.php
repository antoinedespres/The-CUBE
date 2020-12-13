<head>
	<link href="/style.css" rel="stylesheet" type="text/css">
</head>
<?php 

include("view/menu.php"); 

echo '
<body class="styleBody">
    <div id="delete">
        <form method="POST">
            <fieldset>
                <legend>Delete a file</legend>
                <label for="fileName">File name: </label>
                <input type="text" name="fileName" required>
            </fieldset>
            <input type="submit" value="Delete">';
            if ($data != null)
                echo '<p>' . $data . '</p>
        </form>
    </div>
</body>';
?>
<script src="script/chat.js.php?FirstName=<?= $_SESSION['FirstName'];?>"></script>
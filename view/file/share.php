<head>
	<link href="/style.css" rel="stylesheet" type="text/css">
</head>
<?php 

include("view/menu.php"); 

echo '
<body class="styleBody">
    <div id="register">
        <form method="POST">
            <fieldset>
                <legend>Share a file to a user</legend>
                <table>
                    <tr>
                        <td>
                            <label for="fileName">* File name: </label>
                            <input type="text" name="fileName" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="email">* User e-mail: </label>
                            <input type="email" name="email" required>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <span>* required field</span>
            <input type="submit" value="Share">';
            if ($data != null)
                echo '<p>' . $data . '</p>
        </form>
    </div>
</body>';
?>

<script src="script/chat.js.php?FirstName=<?= $_SESSION['FirstName'];?>"></script>
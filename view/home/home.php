<?php include("view/menu.php");?>
<h1>Welcome to Bananuage!</h1>
<?php 

if (isset($_SESSION['FirstName']))
{
    echo 'Welcome ' . $_SESSION['FirstName'] . " .";
}
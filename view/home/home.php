<?php include("view/menu.php");

echo '$_SESSION contains: ';
print_r($_SESSION);

if (isset($_SESSION['UserID']) AND isset($_SESSION['FirstName']))
{
    echo ' The connected user is ' . $_SESSION['FirstName'] . " and his UserID is " . $_SESSION['UserID'];
}
?>

<h1>Welcome to Bananuage!</h1>
<?php 

echo '
    <head>
        <link href="./style.css" rel="stylesheet" type="text/css">
    </head>
    <body id="home">
        <div id="container">';
    if(isset($_SESSION['UserID'])){
        echo '
            <h1>Welcome ' . $_SESSION['FirstName'] . "!</h1>";
        include("view/menu.php");
        echo '</div>';
    }
    else
        echo'
            <img id="logo" src="/assets/thecube.png"></img>;
            <h1>Welcome to the CUBE!</h1>
            <div class="containerChild">
                <span class="button"><a href="login">Sign in</a></span>
                <span class="button"><a href="register">Sign up</a></span>
            </div>
        </div>
    </body>';

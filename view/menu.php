<?php
       echo '
       <head>
	       <link href="style.css" rel="stylesheet" type="text/css">
       </head> 

       <nav>
              <ul class="menu">
                     <li>
                            <a href="/">Home</a>
                     </li> 
                     <li>
                            <a href="users">Users list</a>
                     </li> 
              </ul>
       </nav>';


       if (isset($_SESSION['UserID'])){
              echo '
              <div class="containerChild">
                     <span class="button"><a href="upload">Upload a file</a></span>
		     <span class="button"><a href="delete">Delete a file</a></span>
                     <span class="button"><a href="share">Share your files</a></span>
                     <span class="button"><a href="yourFiles">Your files</a></span>
                     <span class="button"><a href="sharedFiles">Your shared files</a></span>
                     <span class="button"><a href="edit">Edit</a></span>
                     <span class="button"><a href="disconnect">Disconnect</a></span>
              </div>';
              include('file/searchBar.php');
       }
?>

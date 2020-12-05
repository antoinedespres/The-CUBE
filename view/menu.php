<?php
       if (isset($_SESSION['UserID'])){
              echo '
              <div class="containerChild">
                     <span class="button"><a href="/">Home</a></span>
                     <span class="button"><a href="users">List</a></span>
                     <span class="button"><a href="uploadFile">Upload a file</a></span>
                     <span class="button"><a href="deleteFile">Delete a file</a></span>
                     <span class="button"><a href="shareFile">Share your files</a></span>
                     <span class="button"><a href="yourFiles">Your files</a></span>
                     <span class="button"><a href="sharedFiles">Your shared files</a></span>
                     <span class="button"><a href="edit">Edit</a></span>
                     <span class="button"><a href="deleteAccount">Delete account</a></span>
                     <span class="button"><a href="disconnect">Disconnect</a></span>
              </div>
       </div>';
              include('file/searchBar.php');
       }
?>

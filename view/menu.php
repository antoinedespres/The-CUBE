<?php
if (isset($_SESSION['UserID'])) {
       echo '
       <a href="/"> <img id="logoConnect" src="/assets/logo_large.png"></img></a>
              <nav>
              
                     <div id="menu">
                            <ul>
                                   <li><a href="/">Home</a></li><!-- @whitespace
                                   --><li><a>File</a>
                                          <ul>
                                                 <li><a href="yourFiles">Show my files</a></li>
                                                 <li><a href="uploadFile">Upload</a></li>
                                                 <li><a href="shareFile">Share</a></li>
                                          </ul>
                                   </li><!-- @whitespace
                                   --><li><a>Account</a>
                                          <ul>
                                                 <li><a href="deleteAccount">Delete</a></li>
                                                 <li><a href="changePassword">Change password</a></li>
                                                 <li><a href="disconnect">Disconnect</a></li>
                                          </ul>
                                   </li>
                            </ul>
                     </div>
              </nav>';
       include('file/searchBar.php');
       include('chat.php');
}
else{
       echo '<a href="/"> <img id="logoDisconnect" src="/assets/logo_large.png"></img></a>';
}

<?php 

echo '<head>
	<link href="style.css" rel="stylesheet" type="text/css">
</head> 
<div id="searchBar">
        <form method="POST" action="/search">
                <input type="search" name="search" list="nameList" placeholder="Search your file">
                <button type="submit">OK</button>
        </form>
</div>';

if (isset($_POST['search'])){
        $files = \Model\File::getFiles2();
        if (isset($files)){
                $string = "";
                foreach ($files as $nom){
                        $string = $string . '<option value="'. $nom['FileName'] .'">';
                }
                echo '<datalist id="nameList">' . $string . '</datalist>';
        }
}


?>
<?php


$files = \Model\File::getFiles(false);
$sharedFiles = \Model\File::getSharedFiles(false);

$string = "";
foreach ($files as $nom) {
        $string = $string . '<option value="' . $nom['FileName'] . '">';
}
foreach ($sharedFiles as $nom) {
        $string = $string . '<option value="' . $nom['FileName'] . '">';
}
echo '<datalist id="nameList" style="height:1.1em; overflow:hidden;">' . $string . '</datalist>';


echo '
        <div id="searchBar">
                <form method="POST" action="/search">
                        <input type="search" name="search" list="nameList" placeholder="Search your file">
                        <button type="submit">OK</button>
                </form>
        </div>';

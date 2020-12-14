<?php
$ownFiles = \Model\File::getFiles(false);
$sharedFiles = \Model\File::getSharedFiles(false);
$string = "";

foreach ($ownFiles as $file) {
        $string = $string . '<option value="' . $file['FileName'] . '">';
}

foreach ($sharedFiles as $file) {
        $string = $string . '<option value="' . $file['FileName'] . '">';
}

echo '<datalist id="nameList">' . $string . '</datalist>';


echo '
        <div id="searchBar">
                <form method="POST" action="/search">
                        <input type="search" name="search" list="nameList" placeholder="Search your file">
                        <button type="submit">OK</button>
                </form>
        </div>';

<?php

$query = file_get_contents("db.sql");
$db = new PDO("sqlite:database.sqlite");
if($db !== null){
    echo "database created";
    $db->exec($query);
}

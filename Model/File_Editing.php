<?php

namespace Model;

class File_Editing{

    public static function findFile(){
        global $db;

   
		$stmt = $db->prepare("select FileName from File where FileID = ?");
		$stmt->bindParam(1, $_GET['file_id'], \PDO::PARAM_INT);
        $stmt->execute();

        // $stmt = $db->prepare("select Path from HasAccessTo where FileID = ?");
        // $stmt->bindParam(1, $file['FileID'], \PDO::PARAM_INT);
        // $stmt->execute();

        return $stmt->fetch();
    }

    public static function findFiles(){
        
        $files = \Model\File::getFiles();
        $fileList = "";
        $dir = './Files/' . $_SESSION["UserID"];

        /*/File_Editing?file_id=<> get par url*/
       
        foreach ($files as $file){
            $fileList = $fileList . '<a href="/File_Editing?file_id='.$file['FileID'].'">' . $file['FileName'] . '</a><br>';
        }
        return $fileList;
        
    }




}
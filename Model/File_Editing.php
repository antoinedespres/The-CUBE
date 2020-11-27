<?php

namespace Model;

class File_Editing{

    public static function findFile($data){
        global $db;

   
		$stmt = $db->prepare("select * from File where FileID = ?");
		$stmt->bindParam(1, $data['fileid'], \PDO::PARAM_INT);
        $stmt->execute();
        $file = $stmt->fetch();

        $stmt = $db->prepare("select Path from HasAccessTo where FileID = ? 
        and UserID = ? ");
        $stmt->bindParam(1, $file['FileID'], \PDO::PARAM_INT);
        $stmt->bindParam(2,$file['UserID'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();


    }


}
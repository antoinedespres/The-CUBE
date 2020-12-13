<?php

namespace Model;

class File_Editing
{
    
    /**
     * Return a file with the File ID
     * @author Ny Andy Randrianarimanana
     * @return file 
     */

    public static function findFile()
    {
        global $db;


        $stmt = $db->prepare("SELECT * FROM File WHERE FileID = ?");
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt->bindParam(1, $_GET['file_id'], \PDO::PARAM_INT);
        }
        else
            $stmt->bindParam(1, $_POST['file_id'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Return True if someone has access to this file (shared)
     * @author Ny Andy Randrianarimanana
     * @param File the file we're investigating
     * @return bool has access to a file or not
     */

    public static function whoHasAccessTo($file){
        global $db;
        $stmt = $db->prepare("SELECT UserID FROM HasAccessTo WHERE FileID = ?");
        $stmt->bindParam(1, $file['FileID'], \PDO::PARAM_INT);
        $stmt->execute();
        // if(!$stmt->execute()){
        //     return false;
        //}
        $userAc = $stmt->fetch();
        if($userAc['UserID'] == $_SESSION['UserID'])
            return true;
        return false;

    }
}

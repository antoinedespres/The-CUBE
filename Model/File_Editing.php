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


        $stmt = $db->prepare("select * from File where FileID = ?");
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $stmt->bindParam(1, $_GET['file_id'], \PDO::PARAM_INT);
        }
        else
            $stmt->bindParam(1, $_POST['file_id'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Gets a list of links to text files to edit
     * @author Ny Andy Randrianarimanana
     * @return string with the links in it
     */
    public static function linkToFiles()
    {

        global $db;
        $files = \Model\File::getFiles(false);

        $share = \Model\File::getSharedFiles(false);
        $fileList = "";

       

        foreach ($files as $file) {
            if($file['Category'] == 'Text')
                $fileList = $fileList . '<a href="/File_Editing?file_id=' . $file['FileID'] . '">' . $file['FileName'] . '</a><br>';
        }

        if (!$share == null) {  
            $fileList = $fileList . ' <h4> Your shared Files : </h4> <br>';
            foreach ($share as $file) {
                if($file['Category'] == 'Text')
                    $fileList = $fileList . '<a href="/File_Editing?file_id=' . $file['FileID'] . '">' . $file['FileName'] . '</a><br>';
            } 
        }

        return $fileList;
    }

    /**
     * Return True if someone has access to this file (shared)
     * @author Ny Andy Randrianarimanana
     * @param File the file we're investigating
     * @return bool has access to a file or not
     */

    public static function whoHasAccessTo($file){
        global $db;
        $stmt = $db->prepare("Select UserID from HasAccessTo where FileID = ?");
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

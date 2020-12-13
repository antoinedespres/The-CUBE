<?php


// $stmt = $db->prepare('SELECT * FROM File as f, User as u WHERE FileID=' . $files['FileID'] . ' AND f.UserID = u.UserID' . ' AND f.Catgeory = "Text" ;');
// $searchValue = '%' . $_POST["search"] . '%';
// $stmt->bindValue(':searchValue', $searchValue, \PDO::PARAM_STR);
// if ($stmt->execute() === false) {
// return 'Error: ' . $stmt->errorCode();

namespace Controller;

class File_Editing
{
    /**
     * Gets the content of the file the User wanna edit
     * @author Ny Andy Randrianarimanana
     * @return string  an error
     */
    public static function fileContent()
    {
        /*$_GET[file_id]*/
        $fileToEdit = \Model\File_Editing::findFile();
            if($_SESSION['UserID'] == $fileToEdit['UserID']){
                $path = "./Files/" . $_SESSION['UserID'] . "/" . $fileToEdit['FileName'];
            }
            else{
                if(\Model\File_Editing::whoHasAccessTo($fileToEdit)){
                    $path = "./Files/" . $fileToEdit['UserID'] . "/" . $fileToEdit['FileName'];
                }
                else{
                    \Controller\File_Editing::showFiles();
                    return;
                }
            }

            $content['ID'] = $fileToEdit['FileID'];
            $content['name'] = $fileToEdit['FileName'];
            if(filesize($path) == 0) {
                $content['content'] = "";
                render('file/File_Edit/File_Editing',  $content);
                return;
            }
            $myfile = fopen($path, "a+") or die("This file doesn't exist");
            $content['content'] = fread($myfile, filesize($path));
            fclose($myfile);
            render('file/File_Edit/File_Editing',  $content);
    }


    /**
     * Renders the page Select_File_To_Edit and edit the content of a file
     * @author Ny Andy Randrianarimanana
     * @return nothing
     */

    public static function showFiles()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            \Controller\File::getFiles();
            return;
        }

        $fileToEdit = \Model\File_Editing::findFile();

        if($_SESSION['UserID'] == $fileToEdit['UserID']){
            $path = "./Files/" . $_SESSION['UserID'] . "/" . $fileToEdit['FileName'];
        }
        else{
            if(\Model\File_Editing::whoHasAccessTo($fileToEdit)){
                $path = "./Files/" . $fileToEdit['UserID'] . "/" . $fileToEdit['FileName'];
            }
            else{
                return 'You are not allowed to open this file !';
            }
        }
        file_put_contents($path, htmlspecialchars($_POST['editedContent']), LOCK_EX);
        \Controller\File::getFiles();
    } 
}
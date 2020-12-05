<?php

namespace Controller;

class File_Editing
{

    public static function fileContent()
    {
        /*$_GET[file_id]*/
        $fileToEdit = \Model\File_Editing::findFile();
        if (substr($fileToEdit['FileName'], -4) == ".txt") {
            $path = "./Files/" . $_SESSION['UserID'] . "/" . $fileToEdit['FileName'];
            $myfile = fopen($path, "a+") or die("This file doesn't exist");
            $content = fread($myfile, filesize($path));
            fclose($myfile);
            render('file/File_Edit/File_Editing',  $content);
        }
        else {
            return 'ERR_NOTEXT';
        }
    }

    // public function editFile()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //         \Controller\File_Editing::showFiles();
    //         return;
    //     }

    // }

    public static function showFiles()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/File_Edit/Select_File_To_Edit', \Model\File_Editing::findFiles());
            return;
        }

        $fileToEdit = \Model\File_Editing::findFile();
        $path = "./Files/" . $_SESSION['UserID'] . "/" . $fileToEdit['FileName'];
        $myTextFileHandler = @fopen($path, "w");
        file_put_contents($myTextFileHandler, htmlspecialchars($_POST['editedContent']));
        fclose($myTextFileHandler);
    }
}

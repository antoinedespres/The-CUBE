<?php

namespace Controller;

class File_Editing
{

    public static function fileContent()
    {
        /*$_GET[file_id]*/
        $fileToEdit = \Model\File_Editing::findFile();
        $myfile = fopen($fileToEdit, "a+") or die("This file doesn't exist");
        $content = fread($myfile, filesize($fileToEdit));
        fclose($fileToEdit);
        render('file/File_Edit/File_Editing',  $content);
    }

    public function editFile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/File_Edit/File_Editing', []);
            return;
        }
        $fileToEdit = \Model\File_Editing::findFile();
        $myTextFileHandler = @fopen($fileToEdit,"r+");
        @ftruncate($myTextFileHandler, 0);
        file_put_contents($fileToEdit, $_POST['editedContent']);

    }

    public static function showFiles(){
        render('file/File_Edit/Select_File_To_Edit', \Model\File_Editing::findFiles());
    }

}

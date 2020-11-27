<?php

namespace Controller;

class File_Editing{

    public function findFile(){
       $fileToEdit = \Model\File_Editing::findFile($_GET);
       render('user/file_editing',);
       $myfile = fopen($fileToEdit, "a+") or die("You're not able to open this file");
       $content = fread($myfile,filesize($fileToEdit));
       fclose($fileToEdit);
       return $content;
    }

    public function editFile(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            render('file/File_Editing', []);
        }


    }

}
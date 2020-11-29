<?php

namespace Controller;

class File
{
    public static function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/upload', []);
            return;
        }

        $response = \Model\File::upload();
        render('file/upload', $response);
    }

    public static function list()
    {
        $files = \Model\File::list();
        render('file/drive', $files);
    }

    public static function share()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/share', []);
            return;
        }


    }

    //---------------------------------------------------------
    // FONCTIONS POUR SEARCH BAR
    public static function searchFiles(){
        
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/searchBar', []);
            return;
            //\Controller\File::list2();
        }
        else{
            \Controller\File::list2(htmlspecialchars($_POST["search"]));
        }
        render('file/searchBar', []);
    }

    public static function list2($nom = null)
    {
        $path = "";
        if (isset($_GET['dir'])) {
            $path = $_GET['dir'];
        }

        $files = \Model\File::list2($path, $nom);
        render('file/drive', $files);
    }
    // FIN FONCTION POUR SEARCH BAR
}

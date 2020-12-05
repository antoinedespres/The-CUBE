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

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/delete', []);
            return;
        }

        $response = \Model\File::delete();
        render('file/delete', $response);
    }
    
    public static function share()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/share', []);
            return;
        }

        $response = \Model\File::share();
        render('file/share', $response);
    }

    public static function list()
    {
        $files = \Model\File::list();
        render('file/drive', $files);
    }

    public static function sharedList()
    {
        $files = \Model\File::sharedList();
        render('file/drive', $files);
    }

    //---------------------------------------------------------
    // FONCTIONS POUR SEARCH BAR
    public static function searchFiles(){
        
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/searchBar', []);
            return;
        }
        else{
            \Controller\File::list2();
        }
    }

    public static function list2($nom = null)
    {
        $path = "";
        if (isset($_GET['dir'])) {
            $path = $_GET['dir'];
        }

        $response = \Model\File::list2();
        render('file/drive', $response); 
    }
    // FIN FONCTION POUR SEARCH BAR
}

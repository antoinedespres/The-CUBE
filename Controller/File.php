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
        $path = "";
        if (isset($_GET['dir'])) {
            $path = $_GET['dir'];
        }

        $files = \Model\File::list($path);
        render('file/drive', $files);
    }

    public static function createDirectory(){
        $path = "";
        if (isset($_GET['dir'])) {
            $path = $_GET['dir'];
        }
        \Model\File::createDirectory($path, "");
        \Controller\File::list($path);
    }
}

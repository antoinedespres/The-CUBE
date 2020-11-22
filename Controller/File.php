<?php

namespace Controller;

class File
{
    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/upload', []);
            return;
        }

        $response = \Model\File::upload();

        render('file/upload', $response);
    }
}

<?php

namespace Controller;

class File
{
    const FILE_ERRORS = [
        'upload' => [
            '0' => 'Your file has been successfully uploaded.',
            '1' => 'File upload failed.',
            '2' => 'Your file has already been uploaded.'
        ],
        'share' => [
            '0' => 'Your file has been successfully shared.',
            '1' => 'File sharing failed.',
            '2' => 'The file does not exists in "Your files".',
            '3' => 'The e-mail is invalid.',
            '4' => 'Your file has already been shared to this user.'
        ]
    ];

    public static function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/upload', []);
            return;
        }

        $response = \Model\File::upload();
        render('file/upload', File::FILE_ERRORS['upload'][$response]);
    }

    public static function delete()
    {
        \Model\File::delete();
        \Controller\File::getFiles();
    }

    public static function share()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/share', []);
            return;
        }

        $response = \Model\File::share();
        render('file/share', File::FILE_ERRORS['share'][$response]);
    }

    public static function getFiles()
    {
        $ownFiles = \Model\File::getFiles(false);
        $sharedFiles = \Model\File::getSharedFiles(false);
        render('file/myFiles', array($ownFiles, $sharedFiles));
    }

    public static function searchFiles()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            render('file/searchBar', []);
            return;
        } else {
            $ownFiles = \Model\File::getFiles(true);
            $sharedFiles = \Model\File::getSharedFiles(true);
            render('file/myFiles', array($ownFiles, $sharedFiles));
        }   
    }

}

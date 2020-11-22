<?php

namespace Model;
/**
 * note : path désigne le chemin du fichier sur le cloud utilisateur
 * 
 */
class File
{
    /**
     * TODO
     * créer un nouveau répertoire au niveau de $path
     * je n'ai pas encore défini comment faire ça
     */
    public static function createDirectory($path)
    {

    }

    /**
     * TODO
     * ajouter le fichier à la bdd
     * upload le fichier dans le dossier de l'utilisateur sur le serv
     * adapter le path à l'endroit où se situe l'utilisateur lorsqu'il upload le ficher
     */
    public static function upload()
    {
        if ($_FILES["file"]["error"] > 0)
            $response['message'] = $_FILES["file"]["error"];
        else {
            if (file_exists("./Files/" . $_FILES["file"]["name"]))
                $response['message'] = $_FILES["file"]["name"] . " already exists.";
            else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "./Files/" . $_FILES["file"]["name"]);
                $data['message'] = $_FILES["file"]["name"] . " has been uploaded.";
            }
        }
        return $response;
    }

    /**
     * TODO
     * affiche la liste des fichiers et répertoires disponibles selon le $path
     * $path désigne l'emplacement actuel de l'utilisateur
     * les répertoires seront des liens cliquables qui appellent cette méthode 
     * les fichiers seront des liens cliquables qui appellent download()
     */
    public static function list($path)
    {

    }

    /**
     * TODO
     * retrouver l'emplacement du fichier sur le serv à partir du $path
     * permet au client de télécharger un fichier
     */
    public static function download($path)
    {

    }

    /**
     * TODO
     * donne accès à un fichier à un user
     */
    public static function share($fileId)
    {

    }

}
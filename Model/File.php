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
    public static function createDirectory($path, $dirName)
    {
        mkdir($path, $dirName);
    }

    /**
     * TODO
     * ajouter le fichier à la bdd
     * upload le fichier dans le dossier de l'utilisateur sur le serv
     * adapter le path à l'endroit où se situe l'utilisateur lorsqu'il upload le ficher
     */
    public static function upload()
    {
        $id = $_SESSION["UserID"];
        $uploadDir = './Files/' . $id;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        if ($_FILES["file"]["error"] > 0)
            $response['message'] = $_FILES["file"]["error"];
        else {
            if (file_exists($uploadDir . "/" . $_FILES["file"]["name"]))
                $response['message'] = $_FILES["file"]["name"] . " already exists.";
            else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDir . "/" . $_FILES["file"]["name"]);
                
                // à changer 
                $categ="unknown";

                global $db;
                
		        $stmt = $db->prepare("insert into File (FileName, Category, UserID) values (?, ?, ?);");
		        $stmt->bindParam(1, $_FILES["file"]["name"], \PDO::PARAM_STR);
		        $stmt->bindParam(2, $categ, \PDO::PARAM_STR);
                $stmt->bindParam(3, $id, \PDO::PARAM_STR);
                $stmt->execute();

                $response['message'] = $_FILES["file"]["name"] . " has been uploaded.";
            }
        }
        return $response;
    }

    /**
     * retourne la liste des fichiers de l'user connecté
     * TODO : ajouter les listes des fichiers accédés seulement en partage
     */
    public static function getFiles()
    {
        global $db;

		$stmt = $db->prepare("select * from file where UserID=" . $_SESSION["UserID"] . ";");
		if ($stmt->execute() === false) { //this is early return
			echo $stmt->errorCode();
			return;
        }

        return $stmt->fetchAll();
    }

    /**
     * TODO
     * affiche la liste des fichiers et répertoires disponibles selon le $path
     * $path désigne l'emplacement actuel de l'utilisateur
     * TODO : afficher les repertoires
     */
    public static function list($path)
    {

        $files = \Model\File::getFiles();
        $fileList = "";
        $dir = './Files/' . $_SESSION["UserID"] . $path;
        foreach ($files as $file){
            $fileList = $fileList . '<a href="' . $dir . "/" . $file['FileName']. '" download>' . $file['FileName'] . '</a><br>';
        }
		return $fileList;
    }

    /**
     * TODO
     * donne accès à un fichier à un user
     * les fichiers partagés seront affiché dans une page de dossier 'shared'
     */
    public static function share($fileId, $userId)
    {
    }

    
    /**
     * TODO
     * permet trouver un fichier / un type de fichier
     */
    public static function searchFile()
    {
        
    }
}

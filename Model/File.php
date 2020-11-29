<?php

namespace Model;

/**
 * note : path désigne le chemin du fichier sur le cloud utilisateur
 * 
 */
class File
{
    /**
     * enregistre le fichier dans le dossier de l'user et dans la bdd
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

    public static function share()
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
     * retourne un tableau contenant les informations des fichiers de l'user
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
     * retourne les liens de téléchargement des fichiers appartenant à l'user
     */
    public static function list()
    {
        $files = \Model\File::getFiles();
        $dir = './Files/' . $_SESSION["UserID"];
        $fileList="";
        foreach ($files as $file){
            $fileList = $fileList . '<a href="' . $dir . "/" . $file['FileName']. '" download>' . $file['FileName'] . '</a><br>';
        }
		return $fileList;
    }
    
    //---------------------------------------------------------------------------
    /**
     * TODO
     * permet trouver un fichier / un type de fichier
     */
    public static function searchFiles()
    {
        $files = \Model\File::list();
		return $files;
    }

        /**
     * retourne la liste des fichiers de l'user connecté
     * TODO : ajouter les listes des fichiers accédés seulement en partage
     */
    public static function getFiles2($nom=null)
    {
        global $db;

        $stmt = $db->prepare("SELECT * from file where UserID=" . $_SESSION["UserID"] . " and FileName LIKE '%?%';");
        $stmt->bindParam(1, $nom, \PDO::PARAM_STR);
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
    public static function list2($path, $nom=null)
    {

        $files = \Model\File::getFiles2($nom);
        $fileList = "";
        $dir = './Files/' . $_SESSION["UserID"] . $path;
        foreach ($files as $file){
            $fileList = $fileList . '<a href="' . $dir . "/" . $file['FileName']. '" download>' . $file['FileName'] . '</a><br>';
        }
		return $fileList;
    }
}

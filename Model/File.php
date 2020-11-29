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
     * attention : limite de 2MB par défaut, à configurer dans php.ini
     */
    public static function upload()
    {
        $id = $_SESSION["UserID"];
        $uploadDir = './Files/' . $id;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        if ($_FILES["file"]["error"] > 0)
            $response = 'Error: ' . $_FILES["file"]["error"];
        else {
            if (file_exists($uploadDir . "/" . $_FILES["file"]["name"]))
                $response = $_FILES["file"]["name"] . " already exists.";
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

                $response = $_FILES["file"]["name"] . " has been uploaded.";
            }
        }
        return $response;
    }

    /**
     * 
     */
    public static function share()
    {
        $shareEmail = $_POST["email"];
        $fileName = $_POST["fileName"];
        
        $fileId = \Model\File::getFile($fileName);
        $userId = \Model\Users::getUser($shareEmail);

        if($fileId == null)
            $response = $fileName . ' does not exist in "Your files."';
        elseif($userId == null)
            $response = $shareEmail . ' is not an user e-mail.';
        else {
            global $db;

            $stmt = $db->prepare("insert into HasAccessTo (FileID, UserID) values (?, ?);");
            $stmt->bindParam(1, $fileId['FileID'], \PDO::PARAM_STR);
            $stmt->bindParam(2, $userId['UserID'], \PDO::PARAM_STR);
            if ($stmt->execute() === false) { //this is early return
                echo $stmt->errorCode();
                return;
            }
            
            $response = $fileName . ' has successfully been shared to ' . $shareEmail;
        }
        return $response;
    }

    public static function getFile($fileName)
    {
        global $db;

		$stmt = $db->prepare("SELECT FileID FROM File where FileName = ?;");
		$stmt->bindParam(1, $fileName, \PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch();
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
     * retourne un tableau contenant les informations des fichiers partagé à l'user
     */
    public static function getSharedFiles()
    {
        global $db;

		$stmt = $db->prepare("select * from HasAccessTo where UserID=" . $_SESSION["UserID"] . ";");
		if ($stmt->execute() === false) { //this is early return
			echo $stmt->errorCode();
			return;
        }

        $sharedFiles = $stmt->fetchAll();

        foreach($sharedFiles as $files)
        $stmt = $db->prepare("select * from File where FileID=" . $files['FileID'] . ";");
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

    /**
     * retourne les liens de téléchargement des fichiers partagé à l'user
     */
    public static function sharedList()
    {
        $files = \Model\File::getSharedFiles();
        $dir = './Files/';
        $fileList="";
        foreach ($files as $file){
            $fileList = $fileList . '<a href="' . $dir . "/" . $file['UserID'] . "/" . $file['FileName']. '" download>' . $file['FileName'] . '</a><br>';
        }
		return $fileList;
    }


    //---------------------------------------------------------------------------
        /**
     * retourne la liste des fichiers de l'user connecté
     * TODO : ajouter les listes des fichiers accédés seulement en partage
     */
    public static function getFiles2()
    {   
        global $db;
        $stmt = $db->prepare("SELECT * from file where UserID=" . $_SESSION["UserID"] . " and FileName LIKE :searchValue;");
        $searchValue = '%'.$_POST["search"].'%';
        $stmt->bindValue(':searchValue', $searchValue, \PDO::PARAM_STR);

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
    public static function list2()
    {
        $files = \Model\File::getFiles2();
        if ($files == null){
            $response = "No existing file or directory named " . $_POST["search"];
        }
        elseif(isset($files)){
            $response = "";
            $dir = './Files/' . $_SESSION["UserID"];
            foreach ($files as $file){
                $response = $response . '<a href="' . $dir . "/" . $file['FileName']. '" download>' . $file['FileName'] . '</a><br>';
            }
        }
		return $response;
    }
}

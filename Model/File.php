<?php

namespace Model;

/**
 * Model that manages the application's files: access, addition, deletion and sharing
 * @author Viviane Qian, Monica Huynh
 */
class File
{
    /**
     * Uploads a file of the currently logged in user to the server
     * The max size depends on your php.ini configuration
     * @author Viviane Qian
     * @see /Model/Controller::FILE_ERRORS
     * @return int containing a response code
     */
    public static function upload()
    {
        $id = $_SESSION['UserID'];
        $userDir = './Files/' . $id;

        if (!is_dir($userDir)) {
            mkdir($userDir);
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] > 0)
            return 1;

        if (file_exists($userDir . '/' . $_FILES['file']['name']))
            return 2;

        move_uploaded_file($_FILES['file']['tmp_name'], $userDir . '/' . $_FILES['file']['name']);
        
        $categ = \Model\File::getCategory($_FILES['file']['name']);

        global $db;

        $stmt = $db->prepare('insert into File (FileName, Category, UserID) values (?, ?, ?);');
        $stmt->bindParam(1, $_FILES['file']['name'], \PDO::PARAM_STR);
        $stmt->bindParam(2, $categ, \PDO::PARAM_STR);
        $stmt->bindParam(3, $id, \PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            return 1;
        }

        return 0;
    }

    /**
     * Deletes permanently a file of the currently logged in user from the server and the database
     * @author Viviane Qian
     * @return bool true if it went well, false if not
     */
    public static function delete()
    {
        if(!isset($_GET['fileName']))
            return false;

        $fileName = $_GET['fileName'];
        $id = $_SESSION['UserID'];

        $file = \Model\File::getFile($fileName, $id);

        if ($file == null)
            return false;

        $userDir = './Files/' . $id;

        unlink($userDir . '/' . $fileName);

        global $db;

        $db->exec('PRAGMA foreign_keys = ON;');
        $stmt = $db->prepare('DELETE FROM File WHERE UserID = ?' . ' AND FileName = ?;');
        $stmt->bindParam(1, $id, \PDO::PARAM_STR);
        $stmt->bindParam(2, $fileName, \PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            return false;
        }

        return true;
    }

    /**
     * Deletes permanently the folder of an user
     * @author Viviane Qian
     * @param int $userID the id of an user
     * @return bool true if it went well, false if not
     */
    public static function deleteFolder($userID)
    {   
        $folder = './Files/' . $userID;

        if (!is_dir($folder))
            return true;

        foreach (scandir($folder) as $file) {
            if($file != '.' and $file != '..')
                unlink($folder . '/' . $file);
        }

        return rmdir($folder);
    }

    /**
     * Shares the access of an user's file to another user
     * @author Viviane Qian
     * @see /Model/Controller::FILE_ERRORS
     * @return int containing a response code
     */
    public static function share()
    {
        if(!isset($_POST['email']) || !isset($_POST['fileName']))
            return 1;

        $shareEmail = strtolower($_POST['email']);
        $fileName = $_POST['fileName'];

        $file = \Model\File::getFile($fileName, $_SESSION['UserID']);
        $shareUserId = \Model\Users::getUser($shareEmail);

        if ($file == null)
            return 2;

        if ($shareUserId['UserID'] == null or $shareUserId['UserID'] == $_SESSION['UserID'])
            return 3;

        global $db;

        $stmt = $db->prepare('INSERT INTO HasAccessTo (FileID, UserID) VALUES (?, ?);');
        $stmt->bindParam(1, $file['FileID'], \PDO::PARAM_STR);
        $stmt->bindParam(2, $shareUserId['UserID'], \PDO::PARAM_STR);
        try {
            if ($stmt->execute() === false) {
                return 1;
            }
        } catch (\PDOException $e) {
            return 4;
        }

        return 0;
    }

    /**
     * Gets a file's info from the database
     * @author Viviane Qian
     * @param string $fileName the name of the file you want info on
     * @param int $userID the ID of the user sharing the file
     * @return array containing the info from the database if it went well, an error if not
     */
    public static function getFile($fileName, $userID)
    {
        global $db;

        $stmt = $db->prepare('SELECT * FROM File where FileName = ? AND UserID = ?;');
        $stmt->bindParam(1, $fileName, \PDO::PARAM_STR);
        $stmt->bindParam(2, $userID, \PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        return $stmt->fetch();
    }

    /**
     * Gets all the files' info of the currently logged in user from the database
     * @author Viviane Qian, Monica Huynh
     * @param bool $search true if searching files based on a string, else false
     * @return array containing the info from the database if it went well, an error if not
     */
    public static function getFiles($search)
    {
        global $db;

        if(!$search){
            $stmt = $db->prepare('SELECT * FROM File WHERE UserID=' . $_SESSION['UserID'] . ';');
        }
        else{
            $stmt = $db->prepare('SELECT * from file where UserID=' . $_SESSION['UserID'] . ' and FileName LIKE :searchValue;');
            $searchValue = '%' . $_POST["search"] . '%';
            $stmt->bindValue(':searchValue', $searchValue, \PDO::PARAM_STR);
        }

        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        return $stmt->fetchAll();
    }

    /**
     * Gets all the info of the files shared to the currently logged in user from the database
     * @author Viviane Qian, Monica Huynh
     * @param bool $search true if searching files based on a string, else false
     * @return array containing the info from the database if it went well, an error if not
     */
    public static function getSharedFiles($search)
    {
        global $db;

        $stmt = $db->prepare('SELECT FileID FROM HasAccessTo WHERE UserID=' . $_SESSION['UserID'] . ';');
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        $sharedFiles = $stmt->fetchAll();
        $queryResult = array();

        foreach ($sharedFiles as $files) {
            if (!$search){
                $stmt = $db->prepare('SELECT * FROM File as f, User as u WHERE FileID=' . $files['FileID'] . ' AND f.UserID = u.UserID;');
            }
            else{
                $stmt = $db->prepare('SELECT * FROM File as f, User as u WHERE FileID=' . $files['FileID'] . ' AND f.UserID = u.UserID' . ' AND f.FileName LIKE :searchValue;');
                $searchValue = '%' . $_POST["search"] . '%';
                $stmt->bindValue(':searchValue', $searchValue, \PDO::PARAM_STR);
            }

            if ($stmt->execute() === false) {
                return 'Error: ' . $stmt->errorCode();
            }
            
            $fileQuery = $stmt->fetch();
            if($fileQuery != null)
                array_push($queryResult, $fileQuery);
        }
        return $queryResult;
    }

    /**
     * Gets the category of a file
     * @author Viviane Qian
     * @param string $fileName the name of the file you want the category
     * @return string containing the category if it went well, null if not
     */
    public static function getCategory($fileName)
    {   
        $extension = explode('.', $fileName);
        if ($extension == null)
            return null;
        if (in_array(strtolower($extension[1]), ['txt', 'php', 'js', 'java', 'py', 'c', 'css', 'csv', 'cpp', 'html', 'cs']))
            return 'Text';
        if (in_array(strtolower($extension[1]), ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg']))
            return 'Image';
        if (in_array(strtolower($extension[1]), ['mp3', 'ogg', 'm4a', 'wav', 'wma']))
            return 'Music';
        if (in_array(strtolower($extension[1]), ['mp4', 'avi', 'mov', 'wmv']))
            return 'Video';
        if (in_array(strtolower($extension[1]), ['exe']))
            return 'Windows executable';
        if (in_array(strtolower($extension[1]), ['zip', '7z', 'rar']))
            return 'Compressed folder';
        if (in_array(strtolower($extension[1]), ['pdf']))
            return 'Document';
        if (in_array(strtolower($extension[1]), ['doc', 'docx', 'docm']))
            return 'Word Document';
        if (in_array(strtolower($extension[1]), ['xlsx', 'xls', 'xlsm']))
            return 'Excel Document';
        if (in_array(strtolower($extension[1]), ['ppt', 'pptx', 'pptm','pot']))
            return 'Powerpoint Document';
        if (in_array(strtolower($extension[1]), ['eml']))
            return 'Outlook mail';
        return 'Unknown';
    }
}

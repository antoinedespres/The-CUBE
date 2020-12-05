<?php

namespace Model;

class File
{
    /**
     * Uploads a file of the currently logged in user to the server
     * The max size depends on your php.ini configuration
     * @author Viviane Qian
     * @return string containing a success message if it went well, an error message if not
     */
    public static function upload()
    {
        $id = $_SESSION['UserID'];
        $userDir = './Files/' . $id;

        if (!is_dir($userDir)) {
            mkdir($userDir);
        }

        if ($_FILES['file']['error'] > 0)
            return 'Error: ' . $_FILES['file']['error'];

        if (file_exists($userDir . '/' . $_FILES['file']['name']))
            return $_FILES['file']['name'] . ' already exists.';

        move_uploaded_file($_FILES['file']['tmp_name'], $userDir . '/' . $_FILES['file']['name']);
        
        $categ = \Model\File::getCategory($_FILES['file']['name']);

        global $db;

        $stmt = $db->prepare('insert into File (FileName, Category, UserID) values (?, ?, ?);');
        $stmt->bindParam(1, $_FILES['file']['name'], \PDO::PARAM_STR);
        $stmt->bindParam(2, $categ, \PDO::PARAM_STR);
        $stmt->bindParam(3, $id, \PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        return $_FILES['file']['name'] . ' has been uploaded.';
    }

    /**
     * Deletes permanently a file of the currently logged in user from the server and the database
     * @author Viviane Qian
     * @return string containing a success message if it went well, an error message if not
     */
    public static function delete()
    {
        $fileName = $_POST['fileName'];
        $id = $_SESSION['UserID'];

        $file = \Model\File::getFile($fileName, $id);

        if ($file == null)
            return $fileName . ' does not exist in "Your files".';

        $userDir = './Files/' . $id;

        unlink($userDir . '/' . $fileName);

        global $db;

        $db->exec('PRAGMA foreign_keys = ON;');
        $stmt = $db->prepare('DELETE FROM File WHERE UserID = ?' . ' AND FileName = ?;');
        $stmt->bindParam(1, $id, \PDO::PARAM_STR);
        $stmt->bindParam(2, $fileName, \PDO::PARAM_STR);
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        return $fileName . ' has successfully been deleted.';
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
            return false;

        foreach (scandir($folder) as $file) {
            if($file == '.' or $file == '..')
                unlink($folder . '/' . $file);
        }

        return rmdir($folder);
    }

    /**
     * Shares the access of an user's file to another user
     * @author Viviane Qian
     * @return string containing a success message if it went well, an error message if not
     */
    public static function share()
    {
        $shareEmail = $_POST['email'];
        $fileName = $_POST['fileName'];

        $file = \Model\File::getFile($fileName, $_SESSION['UserID']);
        $shareUserId = \Model\Users::getUser($shareEmail);

        if ($file == null)
            return $fileName . ' does not exist in "Your files".';

        if ($shareUserId == null or $shareUserId == $_SESSION['UserID'])
            return $shareEmail . ' is not a valid e-mail.';

        global $db;

        $stmt = $db->prepare('INSERT INTO HasAccessTo (FileID, UserID) VALUES (?, ?);');
        $stmt->bindParam(1, $file['FileID'], \PDO::PARAM_STR);
        $stmt->bindParam(2, $shareUserId['UserID'], \PDO::PARAM_STR);
        try {
            if ($stmt->execute() === false) {
                return 'Error: ' . $stmt->errorCode();
            }
        } catch (\PDOException $e) {
            return $fileName . ' has already been shared to ' . $shareEmail;
        }

        return $fileName . ' has successfully been shared to ' . $shareEmail;
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
     * @author Viviane Qian
     * @return array containing the info from the database if it went well, an error if not
     */
    public static function getFiles()
    {
        global $db;

        $stmt = $db->prepare('SELECT * FROM File WHERE UserID=' . $_SESSION['UserID'] . ';');
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        return $stmt->fetchAll();
    }

    /**
     * Gets all the info of the files shared to the currently logged in user from the database
     * @author Viviane Qian
     * @return array containing the info from the database if it went well, an error if not
     */
    public static function getSharedFiles()
    {
        global $db;

        $stmt = $db->prepare('SELECT * FROM HasAccessTo WHERE UserID=' . $_SESSION['UserID'] . ';');
        if ($stmt->execute() === false) {
            return 'Error: ' . $stmt->errorCode();
        }

        $sharedFiles = $stmt->fetchAll();
        $queryResult = array();

        foreach ($sharedFiles as $files) {
            $stmt = $db->prepare('SELECT * FROM File as f, User as u WHERE FileID=' . $files['FileID'] . ' AND f.UserID = u.UserID;');
            if ($stmt->execute() === false) {
                return 'Error: ' . $stmt->errorCode();
            }
            array_push($queryResult, $stmt->fetch());
        }
        return $queryResult;
    }

    /**
     * Gets the download link of all the files of the currently logged in user
     * @author Viviane Qian
     * @return string containing the formatted download links
     */
    public static function list()
    {
        $files = \Model\File::getFiles();
        $dir = './Files/' . $_SESSION["UserID"];
        $fileList = 
            '<table>
                <thead>
                    <tr>
                        <th colspan="3">Your files</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>File</td>
                    <td>Category</td>
                    <td>Upload time</td>
                </tr>';

        foreach ($files as $file) {
            $fileList = $fileList . 
                '<tr>
                    <td><a href="' . $dir . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td>
                </tr>';
        }
        $fileList = $fileList . 
                '</tbody>
            </table>';
        return $fileList;
    }

    /**
     * Gets the download link of all the files shared to the currently logged in user
     * @author Viviane Qian
     * @return string containing the formatted download links
     */
    public static function sharedList()
    {
        $files = \Model\File::getSharedFiles();
        $dir = './Files/';
        $fileList = 
            '<table>
                <thead>
                    <tr>
                        <th colspan="4">Your shared files</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>File</td>
                    <td>Category</td>
                    <td>Upload time</td>
                    <td>Owner</td>
                </tr>';

        foreach ($files as $file) {
            $fileList = $fileList . 
                '<tr>
                    <td><a href="' . $dir . '/' . $file['UserID'] . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td> 
                    <td>' . $file['Email'] . '</td> 
                </tr>';
        }
        $fileList = $fileList . 
                '</tbody>
            </table>';
        return $fileList;
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
        if (in_array(strtolower($extension[1]), ['txt', 'php', 'js', 'java', 'py', 'c', 'css', 'cpp']))
            return 'Text';
        if (in_array(strtolower($extension[1]), ['jpg', 'jpeg', 'png']))
            return 'Image';
        if (in_array(strtolower($extension[1]), ['mp3', 'ogg', 'm4a']))
            return 'Music';
        if (in_array(strtolower($extension[1]), ['mp4', 'avi', 'mov']))
            return 'Video';
        return 'Unknown';
    }

    //---------------------------------------------------------------------------
    /**
     * retourne la liste des fichiers de l'user connecté
     * TODO : ajouter les listes des fichiers accédés seulement en partage
     */
    public static function getFiles2()
    {
        global $db;
        $stmt = $db->prepare('SELECT * from file where UserID=' . $_SESSION['UserID'] . ' and FileName LIKE :searchValue;');
        $searchValue = '%' . $_POST["search"] . '%';
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
        if ($files == null) {
            $response = "No existing file or directory named " . $_POST["search"];
        } elseif (isset($files)) {
            $response = "";
            $dir = './Files/' . $_SESSION["UserID"];
            foreach ($files as $file) {
                $response = $response . '<a href="' . $dir . "/" . $file['FileName'] . '" download>' . $file['FileName'] . '</a><br>';
            }
        }
        return $response;
    }
}

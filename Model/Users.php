<?php

namespace Model;

class Users
{
    public static function register($data)
    {
        global $db;

        $password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

		$stmt = $db->prepare("insert into User (FirstName, LastName, Email, Password, RecoveryEmail, PhoneNumber, BirthDate) values (?, ?, ?, ?, ?, ?, ?);");
		$stmt->bindParam(1, $data['firstName'], \PDO::PARAM_STR);
		$stmt->bindParam(2, $data['lastName'], \PDO::PARAM_STR);
		$stmt->bindParam(3, $data['email'], \PDO::PARAM_STR);
		$stmt->bindParam(4, $password, \PDO::PARAM_STR);
		$stmt->bindParam(5, $data['recoveryEmail'], \PDO::PARAM_STR);
		$stmt->bindParam(6, $data['phoneNumber'], \PDO::PARAM_STR);
		$stmt->bindParam(7, $data['birthDate'], \PDO::PARAM_STR);
		$stmt->execute();
    }

    public static function login($data)
    {
        global $db;

		$stmt = $db->prepare("select * from user where Email = ?");
		$stmt->bindParam(1, $data['email'], \PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		if (password_verify($data['password'], $user['Password'])) {
			$_SESSION['UserID'] = $user['UserID'];
			$_SESSION['FirstName'] = $user['FirstName'];
			render('home/home', []);
		} else {
			echo "wrong password";
		}
    }

    public static function list()
    {
        global $db;

		$stmt = $db->prepare("select * from user");
		if ($stmt->execute() === false) {
			echo $stmt->errorCode();
			return;
		}
		$users = $stmt->fetchAll();
		render('user/list', $users);
    }
}
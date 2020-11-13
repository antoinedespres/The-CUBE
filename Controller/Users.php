<?php

namespace Controller;

class Users
{
	public function register()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/register_form', []);
			return;
		}

		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);
		$currentDate = date('Ymd');
		
		global $db;

		$stmt = $db->prepare("insert into user (FirstName, LastName, Email, Password, RecoveryEmail, PhoneNumber, BirthDate) values (?, ?, ?, ?, ?, ?, ?);");
		$stmt->bindParam(1, $_POST['firstName'], \PDO::PARAM_STR);
		$stmt->bindParam(2, $_POST['lastName'], \PDO::PARAM_STR);
		$stmt->bindParam(3, $_POST['email'], \PDO::PARAM_STR);
		$stmt->bindParam(4, $password, \PDO::PARAM_STR);
		$stmt->bindParam(5, $_POST['recoveryEmail'], \PDO::PARAM_STR);
		$stmt->bindParam(6, $_POST['phoneNumber'], \PDO::PARAM_STR);
		$stmt->bindParam(7, $_POST['birthDate'], \PDO::PARAM_STR);
		$stmt->execute();
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', []);
			return;
		}

		global $db;
		$stmt = $db->prepare("select * from user where Email = ?");
		$stmt->bindParam(1, $_POST['email'], \PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		if (password_verify($_POST['password'], $user['Password'])) {
			render('user/home', []);
		} else {
			echo "wrong password";
		}
	}

	public function list()
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

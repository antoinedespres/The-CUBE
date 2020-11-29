<?php

namespace Model;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

require 'lib/vendor/autoload.php';

class Users
{
	public static function register()
	// TODO Check if email not already in db, recovery mail different from email if not empty and User is at least 13 years old
	{
		global $db;

		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);

		$stmt = $db->prepare("insert into User (FirstName, LastName, Email, Password, RecoveryEmail, PhoneNumber, BirthDate) values (?, ?, ?, ?, ?, ?, ?);");
		$stmt->bindParam(1, $_POST['firstName'], \PDO::PARAM_STR);
		$stmt->bindParam(2, $_POST['lastName'], \PDO::PARAM_STR);
		$stmt->bindParam(3, $_POST['email'], \PDO::PARAM_STR);
		$stmt->bindParam(4, $password, \PDO::PARAM_STR);
		$stmt->bindParam(5, $_POST['recoveryEmail'], \PDO::PARAM_STR);
		$stmt->bindParam(6, $_POST['phoneNumber'], \PDO::PARAM_STR);
		$stmt->bindParam(7, $_POST['birthDate'], \PDO::PARAM_STR);
		$stmt->execute();
	}

	public static function isRegisterValid()
	{
		$valid = true;
		if ($_POST['email']);
	}

	/**
	 * return the UserId correponding to the email, null if not found
	 */
	public static function getUser($email){
		global $db;

		$stmt = $db->prepare("SELECT UserID FROM User where Email = ?;");
		$stmt->bindParam(1, $email, \PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch();
	}

	public static function login()
	{
		global $db;

		$stmt = $db->prepare("SELECT * FROM User where Email = ?;");
		$stmt->bindParam(1, $_POST['email'], \PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		if (isset($_POST['email'])) {
			if (password_verify($_POST['password'], $user['Password'])) {
				$_SESSION['UserID'] = $user['UserID'];
				$_SESSION['FirstName'] = $user['FirstName'];
				render('drive', []);
			} else {
				return "ERR_WRONGPASSWORD";
			}
		} else {
			return "ERR_NOACCOUNT";
		}
	}

	public static function list()
	{
		global $db;

		$stmt = $db->prepare("SELECT * FROM User;");
		if ($stmt->execute() === false) { //this is early return
			echo $stmt->errorCode();
			return;
		}
		return $stmt->fetchAll();
	}

	public static function forgottenPassword($data)
	{
		$error = "";
		global $db;
		$email = $data['email'];
		//ERR_EMPTYEMAIL if mail empty -> comparer avec cette constante dans la vue
		// If not null and not empty
		if (isset($email) && (!empty($email))) {
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);

			// If email is incorrect
			if (!$email) {
				return "ERR_INVALIDEMAIL";
			} else {
				$stmt = $db->prepare("SELECT * FROM User WHERE Email = ?");
				$stmt->bindParam(1, $email, \PDO::PARAM_STR);
				$stmt->execute();
				$count = $stmt->rowCount();
				$user = $stmt->fetch();
				print_r($user);
			}

			if ($count == 0) {
				return "ERR_NORESULT";
			}

			// If there is an error (invalid email)
			if ($error != "") {
				echo "<div class='error'>" . $error . "</div>
   <br /><a href='javascript:history.go(-1)'>Go Back</a>";
			} else { // DB update, mail sending
				$expFormat = mktime(
					date("H"),
					date("i"),
					date("s"),
					date("m"),
					date("d") + 1,
					date("Y")
				);
				$expDate = date("Y-m-d H:i:s", $expFormat);

				$key = md5(strval(2418 * 2) & $email);
				$addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
				$key = $key . $addKey;

				// Insert Temp Table
				$stmt = $db->prepare("INSERT INTO PasswordResetTemp(Email, Key, ExpiryDate) VALUES (?, ?, ?);");
				$stmt->bindParam(1, $email, \PDO::PARAM_STR);
				$stmt->bindParam(2, $key, \PDO::PARAM_STR);
				$stmt->bindParam(3, $expDate, \PDO::PARAM_STR);
				$stmt->execute();

				// Mail preparation

				$body = '<p>Dear user,</p>';
				$body .= '<p>Please click on the following link to reset your password.</p>';
				$body .= '<p>-------------------------------------------------------------</p>';
				$body .= '<p><a href="localhost/bananuage/resetpassword.php?key=' . $key . '&email=' . $email . '&action=reset" target="_blank">localhost/bananuage/resetpassword.php?key=' . $key . '&email=' . $email . '&action=reset</a></p>';
				$body .= '<p>-------------------------------------------------------------</p>';

				$body .= '<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';
				$body .= '<p>Thanks,</p>';
				$body .= '<p>Bananuage Account Services</p>';

				$mail = new PHPMailer();

				$mail->IsSMTP();
				$mail->Host = "smtp.gmail.com"; // Enter your host here
				$mail->SMTPAuth = true;
				$mail->Username = "your email"; // Enter your email here
				$mail->Password = "yourPassword"; //Enter your password here
				$mail->Port = 587;

				$mail->IsHTML(true);

				$mail->From = "noreply@bananuage.com";
				$mail->FromName = "Bananuage Account Services";
				$mail->Sender = 'noreply@bananuage.com';
				$mail->SetFrom('noreply@bananuage.com', 'Bananuage');
				$mail->AddReplyTo('noreply@bananuage.com', 'Bananuage');

				$mail->Subject = 'Bananuage password request';
				$mail->Body = $body;
				$mail->AddAddress($email);
				if (!$mail->Send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "<div class='error'>
<p>An email has been sent to you with instructions on how to reset your password.</p>
</div><br /><br /><br />";
				}
			}
		} else {
		}
	}

	public static function resetPassword($data)
	{
		// WORK IN PROGRESS...
	}

	public static function disconnect()
	{
		$_SESSION = null;
	}
}

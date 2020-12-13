<?php

namespace Model;

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

require 'lib/vendor/autoload.php';

class Users
{
	public static function register()
	{
		global $db;
		$email = strtolower($_POST['email']);

		if (isset($email) && (!empty($email))) {
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);

			// If email is incorrect
			if (!$email) {
				return "ERR_INVALIDEMAIL";
			}
		} else {
			return 'ERR_INVALIDEMAIL';
		}

		if (strlen($_POST['firstName']) == 0 || strlen($_POST['lastName']) == 0) 
			return "ERR_EMPTYFIELD";
		
		if(strlen($_POST['password']) < 8)
			return 'ERR_PWDTOOSHORT';

		if (strtotime($_POST['birthDate']) > strtotime('-13 years'))
			return 'ERR_TOOYOUNG';

		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12]);

		$stmt = $db->prepare("INSERT INTO User (FirstName, LastName, Email, Password, BirthDate) values (?, ?, ?, ?, ?);");
		$stmt->bindParam(1, $_POST['firstName'], \PDO::PARAM_STR);
		$stmt->bindParam(2, $_POST['lastName'], \PDO::PARAM_STR);
		$stmt->bindParam(3, $email, \PDO::PARAM_STR);
		$stmt->bindParam(4, $password, \PDO::PARAM_STR);
		$stmt->bindParam(5, $_POST['birthDate'], \PDO::PARAM_STR);
		try {
			$stmt->execute();
		} catch (\PDOException $e) {
			return 'ERR_EXISTINGACC';
		}

		// Connect the new user

		$stmt = $db->prepare("SELECT UserID, FirstName FROM User WHERE Email = ?;");
		$stmt->bindParam(1, $email, \PDO::PARAM_STR);
		$stmt->execute();

		$user = $stmt->fetch();
		$_SESSION['UserID'] = $user['UserID'];
		$_SESSION['FirstName'] = $user['FirstName'];

		if (!Users::updateTimestamp($_SESSION['UserID']))
			return 'ERR_TIMESTAMPFAIL';

		return 'OK_REGISTERED';
	}

	/**
	 * Gets the UserID of the correponding e-mail from the database
	 * 
	 * @author Viviane Qian
	 * @return int the UserID if it exists, null if not
	 */
	public static function getUser($email)
	{
		global $db;

		$stmt = $db->prepare("SELECT UserID FROM User where Email = ?;");
		$stmt->bindParam(1, $email, \PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Login function
	 * 
	 * @author Antoine Després
	 * @return string error code
	 */
	public static function login()
	{
		global $db;

		$email = strtolower($_POST['email']);

		if (isset($email) && (!empty($email))) {
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);

			// If email is incorrect
			if (!$email) {
				return "ERR_INVALIDEMAIL";
			}
		} else {
			return 'ERR_INVALIDEMAIL';
		}

		$stmt = $db->prepare("SELECT * FROM User where Email = ?;");
		$stmt->bindParam(1, $email, \PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();

		if ($user == null)
			return 'ERR_NOACCOUNT';

		if (password_verify($_POST['password'], $user['Password'])) {
			$_SESSION['UserID'] = $user['UserID'];
			$_SESSION['FirstName'] = $user['FirstName'];

			if (!Users::updateTimestamp($_SESSION['UserID']))
				return 'ERR_TIMESTAMPFAIL';
			return ('OK_CONNECTED');
		}
		return "ERR_WRONGPASSWORD";
	}

	/**
	 * Updates the last login timestamp
	 * 
	 * @author Antoine Després
	 * @return true if the specified user exists, false otherwise
	 */
	public static function updateTimestamp($UserID)
	{
		global $db;
		$stmt = $db->prepare("SELECT * FROM User WHERE UserID = ?;");
		$stmt->bindParam(1, $UserID, \PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();

		if ($user != null) {
			$currentTimestamp = date("Y-m-d H:i:s");
			$stmt = $db->prepare("UPDATE User SET LastLoginTime = ? WHERE UserID = ?;");
			$stmt->bindParam(1, $currentTimestamp, \PDO::PARAM_STR);
			$stmt->bindParam(2, $UserID, \PDO::PARAM_STR);
			$stmt->execute();
			return true;
		}
		return false;
	}

	/**
	 * Creates a record in a temporary table and sends an email with a reset token
	 * 
	 * @author Antoine Després
	 * @return string error code
	 */
	public static function forgottenPassword()
	{
		global $db;

		$email = strtolower($_POST['email']);

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
				$user = $stmt->fetch();
			}

			if ($user == null) {
				return "ERR_NOACCOUNT";
			}

			$expFormat = mktime(
				date("H"),
				date("i"),
				date("s"),
				date("m"),
				date("d") + 1,
				date("Y")
			);

			$expiryDate = date("Y-m-d H:i:s", $expFormat);

			$key = md5(strval(2418 * 2) & $email);
			$addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
			$key = $key . $addKey;

			// Temp table : delete previous recording
			$stmt = $db->prepare("DELETE FROM PasswordResetTemp WHERE Email = ?");
			$stmt->bindParam(1, $email, \PDO::PARAM_STR);
			$stmt->execute();

			$stmt = $db->prepare("INSERT INTO PasswordResetTemp(Email, Key, ExpiryDate) VALUES (?, ?, ?);");
			$stmt->bindParam(1, $email, \PDO::PARAM_STR);
			$stmt->bindParam(2, $key, \PDO::PARAM_STR);
			$stmt->bindParam(3, $expiryDate, \PDO::PARAM_STR);
			$stmt->execute();

			// Mail preparation
			global $config;

			$body = '<p>Dear The CUBE user,</p>';
			$body .= '<p>We have received a password reset request for your account. Please click on the following link to reset your password.</p>';
			$body .= '<p>-------------------------------------------------------------</p>';
			$body .= '<p><a href="' . $config['server']['URL'] . '/resetPassword?key=' . $key . '&email=' . $email . '" target="_blank">Reset password</a></p>';
			$body .= '<p>-------------------------------------------------------------</p>';

			$body .= '<p>If you did not make this request, no action 
is needed, your password will not be reset.</p>';
			$body .= '<p>Best regards,</p>';
			$body .= '<p>The CUBE Account Services</p>';

			$mail = new PHPMailer();

			$mail->IsSMTP();
			$mail->Host = $config['mail']['host'];
			$mail->SMTPAuth = true;
			$mail->Username = $config['mail']['email'];
			$mail->Password = $config['mail']['password'];
			$mail->Port = $config['mail']['port'];

			$mail->IsHTML(true);

			$mail->From = "noreply@thecube.com";
			$mail->FromName = "The CUBE Account Services";
			$mail->Sender = 'noreply@thecube.com';
			$mail->SetFrom('noreply@thecube.com', 'The CUBE');
			$mail->AddReplyTo('noreply@thecube.com', 'The CUBE');

			$mail->Subject = 'The CUBE password request';
			$mail->Body = $body;
			$mail->AddAddress($email);
			if (!$mail->Send())
				return 'ERR_MAILER';
			return 'OK_MAILSENT';
		}
	}

	/**
	 * Reads the token and email in the url to load a reset password form
	 * 
	 * @author Antoine Després
	 * @return string error code
	 */
	public static function resetPassword()
	{
		global $db;

		$email = $_SESSION['email'];
		$key = $_SESSION['key'];
		$_SESSION = array();

		$currentDate = date("Y-m-d H:i:s");

		if (isset($key) && isset($email)) {
			$stmt = $db->prepare("SELECT * FROM PasswordResetTemp WHERE Email = ? and Key = ?;");
			$stmt->bindParam(1, $email, \PDO::PARAM_STR);
			$stmt->bindParam(2, $key, \PDO::PARAM_STR);
			$stmt->execute();
			$record = $stmt->fetch();

			if ($record == null)
				return 'ERR_NORESETQUERY';
			

			$expiryDate = $record['ExpiryDate'];

			if ($expiryDate < $currentDate)
				return 'ERR_EXPIREDLINK';
			

			$password1 = $_POST['password1'];
			$password2 = $_POST['password2'];

			if ($password1 != $password2)
				return 'ERR_PWDMISMATCH';
			
			if(strlen($password1) < 8)
				return 'ERR_PWDTOOSHORT';
			
			$password1 = password_hash($password1, PASSWORD_BCRYPT, ['cost' => 12]);;

			// Password change in DB
			$stmt = $db->prepare("UPDATE User SET Password = ? WHERE Email = ?;");
			$stmt->bindParam(1, $password1, \PDO::PARAM_STR);
			$stmt->bindParam(2, $email, \PDO::PARAM_STR);
			$stmt->execute();

			// Delete record in the temporary table
			$stmt = $db->prepare("DELETE FROM PasswordResetTemp Where Email = ?;");
			$stmt->bindParam(1, $email, \PDO::PARAM_STR);
			$stmt->execute();
			return 'OK_PWDCHANGED';
		}
	}

	/**
	 * Change password function
	 * 
	 * @author Antoine Després
	 * @return string error code
	 */
	public static function changePassword()
	{
		global $db;

		if (!isset($_SESSION['UserID']))
			return 'ERR_NOSESSION';

		$stmt = $db->prepare("SELECT * FROM User WHERE UserID = ?");
		$stmt->bindParam(1, $_SESSION['UserID'], \PDO::PARAM_STR);
		$stmt->execute();

		$user = $stmt->fetch();

		$password0 = $_POST['password0'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		if (!password_verify($password0, $user['Password']))
			return 'ERR_WRONGPASSWORD';

		if ($password1 != $password2)
			return 'ERR_PWDMISMATCH';

		if (strlen($password1) < 8)
			return 'ERR_PWDTOOSHORT';

		$password1 = password_hash($password1, PASSWORD_BCRYPT, ['cost' => 12]);

		$stmt = $db->prepare("UPDATE User SET Password = ? WHERE UserID = ?;");
		$stmt->bindParam(1, $password1, \PDO::PARAM_STR);
		$stmt->bindParam(2, $_SESSION['UserID'], \PDO::PARAM_STR);
		$stmt->execute();

		return 'OK_PWDCHANGED';
	}

	/**
	 * Disconnects the user by emptying the session array
	 * 
	 * @author Antoine Després
	 */
	public static function disconnect()
	{
		$_SESSION = array();
	}

	/**
	 * Account deletion function. Checks the password before deleting.
	 * 
	 * @author Antoine Després
	 * @return string error code
	 */
	public static function deleteAccount()
	{
		global $db;

		if (isset($_SESSION['UserID'])) {
			$stmt = $db->prepare("SELECT * FROM User WHERE UserID = ?;");
			$stmt->bindParam(1, $_SESSION['UserID'], \PDO::PARAM_STR);
			$stmt->execute();
			$user = $stmt->fetch();

			if (password_verify($_POST['password'], $user['Password'])) {
				$db->exec('PRAGMA foreign_keys = ON;');
				$stmt = $db->prepare("DELETE FROM User WHERE UserID = ?;");
				$stmt->bindParam(1, $_SESSION['UserID'], \PDO::PARAM_STR);
				$stmt->execute();
				\Model\File::deleteFolder($_SESSION['UserID']);
				$_SESSION = array();
				return 'OK_ACCOUNTDELETED';
			}
			return 'ERR_WRONGPASSWORD';
		} else {
			return 'ERR_NOTCONNECTED';
		}
	}
}

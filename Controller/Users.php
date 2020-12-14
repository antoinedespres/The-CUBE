<?php

namespace Controller;

/**
 * Controller that manages user accounts
 * @author Antoine Després
 */
class Users
{
	const USERS_ERRORS = [
		'register' => [
			'ERR_TOOYOUNG' => 'You need to be at least 13 years old to use our services.',
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_EXISTINGACC' => 'There is an existing account with this email address.',
			'ERR_EMPTYFIELD' => 'One or several required fields are empty.',
			'ERR_TOOYOUNG' => 'You need to be aged 13 or higher to use our services.',
			'ERR_PWDTOOSHORT' => 'Password needs to be at least 8 characters long.',
			'OK_REGISTERED' => ''
		],
		'login' => [
			'ERR_NOACCOUNT' => 'There is no account associated with this email address.',
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_WRONGPASSWORD' => 'Wrong password.',
			'OK_CONNECTED' => 'You have been successfully connected.'
		],
		'forgottenPassword' => [
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_MAILER' => 'Something wrong happend with our mailer service. Sorry for the inconvenience.',
			'ERR_NOACCOUNT' => 'There is no account associated with this email address.',
			'OK_MAILSENT' => 'An email have been sent with instructions to reset your password.'
		],
		'resetPassword' => [
			'ERR_NORESETQUERY' => 'There is no password reset request for this account, or this account does not exist.',
			'ERR_EXPIREDLINK' => 'The reset link has expired. You can do another password reset request.',
			'ERR_PWDMISMATCH' => 'The two passwords do not match.',
			'ERR_PWDTOOSHORT' => 'Password needs to be at least 8 characters long.',
			'OK_PWDCHANGED' => 'Your password has been changed.'
		],
		'changePassword' => [
			'ERR_WRONGPASSWORD' => 'The current password is incorrect',
			'ERR_PWDMISMATCH' => 'New password and its confirmation must not be different.',
			'ERR_PWDTOOSHORT' => 'Password needs to be at least 8 characters long.',
			'OK_PWDCHANGED' => 'Your password has been changed.'
		],
		'deleteAccount' => [
			'ERR_WRONGPASSWORD' => 'Wrong password.',
			'ERR_NOTCONNECTED' => 'You need to be connected to perform this action.',
			'OK_ACCOUNTDELETED' => 'Account successfully deleted.'
		]
	];

	public function register()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/register_form', '');
			return;
		}

		$response = \Model\Users::register();
		if ($response == 'OK_REGISTERED') {
			render('home/home');
		} else {
			render('user/register_form', Users::USERS_ERRORS['register'][$response]);
		}
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', '');
			return;
		}

		$response = \Model\Users::login();
		if ($response == 'OK_CONNECTED') {
			render('home/home');
		} else {
			render('user/login_form', Users::USERS_ERRORS['login'][$response]);
		}
	}

	public function forgottenPassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/forgottenPassword_form', '');
			return;
		}
		$response = \Model\Users::forgottenPassword($_POST);
		render('user/forgottenPassword_form', Users::USERS_ERRORS['forgottenPassword'][$response]);
	}

	public function resetPassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$_SESSION['key'] = $_GET['key'];
			$_SESSION['email'] = $_GET['email'];
			render('user/modifyPassword_form', ['reset']);
			return;
		}
		$data[0] = 'reset';

		if (isset($data[1])) {
			if ($data[1] == 'OK_PWDCHANGED')
				render('home/home');
		} else {
			$data[1] = Users::USERS_ERRORS['resetPassword'][\Model\Users::resetPassword($_POST)];
			render('user/modifyPassword_form', $data);
		}
	}

	public function disconnect()
	{
		\Model\Users::disconnect($_POST);
		render('home/home');
	}

	public function deleteAccount()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/deleteAccount_form', '');
			return;
		}
		$response = \Model\Users::deleteAccount();
		if ($response == 'OK_ACCOUNTDELETED') {
			render('home/home');
			return;
		}
		render('user/deleteAccount_form', Users::USERS_ERRORS['deleteAccount'][$response]);
	}

	public function changePassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/modifyPassword_form', ['change']);
			return;
		}
		$response = \Model\Users::changePassword();
		$data[0] = 'change';
		$data[1] = Users::USERS_ERRORS['changePassword'][$response];

		if ($response == 'OK_PWDCHANGED')
			render('home/home', '');
		else
			render('user/modifyPassword_form', $data);
	}
}

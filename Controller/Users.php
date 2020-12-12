<?php

namespace Controller;

class Users
{
	const USERS_ERRORS = [
		'register' => [
			'ERR_TOOYOUNG' => 'You need to be at least 13 years old to use our services.',
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_EXISTINGACC' => 'There is an existing account with this email address.',
			'ERR_EMPTYFIELD' => 'One or several required fields are empty.',
			'ERR_TOOYOUNG' => 'You need to be aged 13 or higher to use our services.',
			'ERR_ALREADYEXISTS' => 'There is an account registered with this email.',
			'OK_REGISTERED' => ''
		],
		'login' => [
			'ERR_NOACCOUNT' => 'There is no account associated with this email address.',
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_WRONGPASSWORD' => 'Wrong password.',
			'OK_CONNECTED' => 'You have been successfully connected.'
		],
		'forgottenPassword' =>[
			'ERR_INVALIDEMAIL' => 'Invalid email format.',
			'ERR_NOACCOUNT' => 'There is no account associated with this email address.',
		],
		'resetPassword' =>[
			'ERR_NORESETQUERY' =>'There is no password reset request for this account, or this account does not exist.',
			'ERR_EXPIREDLINK' => 'The reset link has expired. You can do another password reset request.',
			'ERR_PWDMISMATCH' => 'The two passwords do not match.',
			'ERR_MAILER' => 'Something wrong happend with our mailer service. Sorry for the inconvenience.',
			'OK_PWDCHANGED' => 'Your password has been changed.'
		],
		'changePassword' =>[
			'ERR_WRONGPASSWORD' => 'The current password is incorrect',
			'ERR_PWDMISMATCH' => 'New password and its confirmation must not be different.'
		],
		'deleteAccount' => [
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

		// redirige vers la page d'accueil pour le moment si l'inscription est réussie
		// faudra probablement changer ça
		$response = \Model\Users::register();
		if($response == 'OK_REGISTERED')
		{
			render('home/home');
		} else{
			render('user/register_form', Users::USERS_ERRORS['register'][$response]);
		}
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', []);
			return;
		}

		$response = \Model\Users::login();
		render('home/home', Users::USERS_ERRORS['login'][$response]);
	}

	public function list()
	{
		$users = \Model\Users::list();
		render('user/list', $users);
	}

	public function forgottenPassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/forgottenPassword_form', []);
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
			render('user/modifyPassword_form', 'reset');
			return;
		}
		$response = \Model\Users::resetPassword();
		render('user/modifyPassword_form', Users::USERS_ERRORS['resetPassword'][$response]);
	}

	public function disconnect()
	{
		\Model\Users::disconnect($_POST);
		render('home/home');
	}

	public function deleteAccount()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/deleteAccount_form', []);
			return;
		}
		$response = \Model\Users::deleteAccount();
		render('user/deleteAccount_form', Users::USERS_ERRORS['deleteAccount'][$response]);	
	}

	public function changePassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/modifyPassword_form', 'change');
			return;
		}
		$response = \Model\Users::changePassword();
		render('user/modifyPassword_form', Users::USERS_ERRORS['changePassword'][$response]);
	}
}

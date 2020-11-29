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

		\Model\Users::register();
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', []);
			return;
		}

		\Model\Users::login();
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
		\Model\Users::forgottenPassword($_POST);
	}

	public function resetPassword()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/resetPassword_form', []);
			return;
		}
		\Model\Users::resetPassword($_POST);
	}

	public function disconnect()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/resetPassword_form', []);
			return;
		}
		\Model\Users::disconnect($_POST);
	}
}

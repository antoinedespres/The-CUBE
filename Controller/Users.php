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

		\Model\Users::register($_POST);
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', []);
			return;
		}

		\Model\Users::login($_POST);
	}

	public function list()
	{
		\Model\Users::list();
	}
}

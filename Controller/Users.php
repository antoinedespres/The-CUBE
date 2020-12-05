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
		// redirige vers la page d'accueil pour le moment si l'inscription est réussie
		// faudra probablement changer ça
		render("home/home");
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			render('user/login_form', []);
			return;
		}

		$response = \Model\Users::login();
		render('home/home', $response);
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
	}
}

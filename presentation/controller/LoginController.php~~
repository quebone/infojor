<?php
namespace tfg\presentation\controller;

class LoginController extends Controller
{
	public function login()
	{
		$username = $_POST[USERNAME];
		$password = $_POST[PASSWORD];
		$model = new \tfg\presentation\model\UserViewModel();
		return $model->login($username, $password);
	}
}

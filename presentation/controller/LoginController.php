<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\UserViewModel;

class LoginController extends Controller
{
	public function login()
	{
		$username = $_POST[USERNAME];
		$password = $_POST[PASSWORD];
		$model = new UserViewModel();
		return $model->login($username, $password);
	}
}

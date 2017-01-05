<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\UserViewModel;
use tfg\service\UserService;

class UserdataController extends Controller
{
	public function getUserData()
	{
		$model = new UserViewModel();
		$userId = $_SESSION[USER_ID];
		return $model->getTeacher($userId);
	}
	
	public function updatePersonalData() {
		$userId = $_POST[USER_ID];
		$name = $_POST[NAME];
		$surnames = $_POST[SURNAMES];
		$email = $_POST[EMAIL];
		$phone = $_POST[PHONE];
		$username = $_POST[USERNAME];
		$password = $_POST[PASSWORD];
		$model = new UserService();
		return $model->updatePersonalData($userId, $name, $surnames, $email, $phone, $username, $password);
	}
}
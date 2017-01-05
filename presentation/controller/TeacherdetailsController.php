<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\UserViewModel;
use tfg\service\UserService;

class TeacherdetailsController extends Controller
{
	public function getTeacherData()
	{
		$model = new UserViewModel();
		$teacherId = $_SESSION[TEACHER_ID];
		return $model->getTeacher($teacherId);
	}
	
	public function resetPassword() {
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->restorePassword($teacherId);
	}

	public function updateTeacher() {
		$teacherId = $_POST[TEACHER_ID];
		$name = $_POST[NAME];
		$surnames = $_POST[SURNAMES];
		$email = $_POST[EMAIL];
		$phone = $_POST[PHONE];
		$username = $_POST[USERNAME];
		$isAdmin = !strcmp($_POST[ISADMIN], "true");
		$model = new UserService();
		return $model->updateTeacher($teacherId, $name, $surnames, $email, $phone, $username, $isAdmin);
	}
}
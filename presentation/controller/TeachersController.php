<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\UserViewModel;
use tfg\service\UserService;

class TeachersController extends Controller
{
	public function getTeachers()
	{
		$model = new UserViewModel();
		return $model->listAllTeachers();
	}
	
	public function editUser()
	{
		$this->setSession();
		// segons si l'usuari actiu és el mateix que el mestre, anem a una pàgina o una altra
		return $_SESSION[USER_ID] == $_SESSION[TEACHER_ID] ? 'userdata.php' : 'teacherdetails.php';
	}
	
	public function deleteUser()
	{
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->deleteTeacher($teacherId);
	}
	
	public function addUser()
	{
		return "teacherdetails.php";
	}
}
<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\SchoolViewModel;
use tfg\presentation\model\UserViewModel;
use tfg\service\UserService;

class ReinforcingsController extends Controller
{
	public function listAllReinforcings()
	{
		$model = new SchoolViewModel();
		return $model->listAllReinforcings();
	}
	
	public function getClassrooms()
	{
		$model = new SchoolViewModel();
		return $model->getReinforceClassrooms();
	}
	
	public function getTeachers()
	{
		$model= new UserViewModel();
		return $model->listAllTeachers();
	}
	
	public function removeReinforcing()
	{
		$classroomId = $_POST[CLASSROOM_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->removeReinforcing($teacherId, $classroomId);
	}
	
	public function addReinforcing()
	{
		$classroomId = $_POST[CLASSROOM_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->addReinforcing($teacherId, $classroomId);
	}
}
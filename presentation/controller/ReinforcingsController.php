<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\presentation\model\UserViewModel;
use infojor\service\UserService;

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

	public function importReinforcingsFromLastCourse()
	{
		$model = new UserService();
		return $model->importReinforcingsFromLastCourse();
	}
}
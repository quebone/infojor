<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\SchoolViewModel;
use tfg\presentation\model\UserViewModel;
use tfg\service\UserService;

class TutoringsController extends Controller
{
	public function listAllTutorings()
	{
		$model = new SchoolViewModel();
		return $model->listAllTutorings();
	}
	
	public function getClassrooms()
	{
		$model = new SchoolViewModel();
		return $model->getClassrooms();
	}
	
	public function getTeachers()
	{
		$model= new UserViewModel();
		return $model->listAllTeachers();
	}
	
	public function removeTutoring()
	{
		$classroomId = $_POST[CLASSROOM_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->removeTutoring($teacherId, $classroomId);
	}
	
	public function addTutoring()
	{
		$classroomId = $_POST[CLASSROOM_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->addTutoring($teacherId, $classroomId);
	}
}
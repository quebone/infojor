<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\presentation\model\UserViewModel;
use infojor\service\UserService;

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
	
	public function importTutoringsFromLastCourse()
	{
		$model = new UserService();
		return $model->importTutoringsFromLastCourse();
	}
}
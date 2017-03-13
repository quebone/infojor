<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\service\UserService;
use infojor\presentation\model\UserViewModel;

class SpecialitiesController extends Controller
{
	public function listAllSpecialities()
	{
		$model = new SchoolViewModel();
		return $model->listAllSpecialities();
	}
	
	public function getAreas()
	{
		$model = new SchoolViewModel();
		return $model->getAreas();
	}
	
	public function getTeachers()
	{
		$model= new UserViewModel();
		return $model->listAllTeachers();
	}
	
	public function removeSpeciality()
	{
		$areaId = $_POST[AREA_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->removeSpeciality($teacherId, $areaId);
	}
	
	public function addSpeciality()
	{
		$areaId = $_POST[AREA_ID];
		$teacherId = $_POST[TEACHER_ID];
		$model = new UserService();
		return $model->addSpeciality($teacherId, $areaId);
	}

	public function importSpecialitiesFromLastCourse()
	{
		$model = new UserService();
		return $model->importSpecialitiesFromLastCourse();
	}
}
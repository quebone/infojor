<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\service\SchoolService;

class CoursesController extends Controller
{
	public function getCourses()
	{
		$model = new SchoolViewModel();
		return $model->listAllCourses();
	}
	
	public function updateCourse()
	{
		$courseId = $_POST[COURSE_ID];
		$year = $_POST[YEAR];
		$isActive = $_POST[ISACTIVE];
		$model = new SchoolService();
		return $model->updateCourse($courseId, $year, $isActive);
	}
	
	public function deleteCourse()
	{
		$courseId = $_POST[COURSE_ID];
		$model = new SchoolService();
		return $model->deleteCourse($courseId);
	}
	
	public function createCourse()
	{
		$year = $_POST[YEAR];
		$model = new SchoolService();
		return $model->createCourse($year);
	}
	
	public function getActiveTrimestre()
	{
		return $this->dao->getSchool()->getActiveTrimestre()->getNumber();
	}
	
	public function setActiveTrimestre()
	{
		$trimestreId = $_POST[TRIMESTRE_ID];
		$model = new SchoolService();
		return $model->setActiveTrimestre($trimestreId);
	}
}
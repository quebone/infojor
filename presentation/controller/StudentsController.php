<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\service\UserService;

class StudentsController extends Controller
{
	public function getClassrooms()
	{
		$model = new SchoolViewModel();
		return $model->getClassrooms();
	}
	
	/**
	 * Obté els alumnes d'una classe i els formata en HTML
	 */
	public function getClassroomStudents()
	{
		$data = array();
		$model = new SchoolViewModel();
		$classroomId = $_POST[CLASSROOM_ID];
		$data['students'] = $model->getCurrentClassroomStudents($classroomId);
		$data['classrooms'] = $model->getClassrooms();
		$template = new \Transphporm\Builder('../../presentation/template/students.inner.xml', '../../presentation/template/students.inner.tss');
		$data = $template->output($data)->body;
		return $data;
	}
	
	public function addStudent()
	{
		$model = new UserService();
		$name = $_POST[NAME];
		$surnames = $_POST[SURNAMES];
		$classroomId = $_POST[CLASSROOM_ID];
		return $model->addStudent($name, $surnames, $classroomId);
	}
	
	public function updateStudent()
	{
		$model = new UserService();
		$studentId = $_POST[STUDENT_ID];
		$name = $_POST[NAME];
		$surnames = $_POST[SURNAMES];
		$classroomId = $_POST[CLASSROOM_ID];
		return $model->updateStudent($studentId, $name, $surnames, $classroomId);
	}
	
	public function deleteStudent()
	{
		$model = new UserService();
		$studentId = $_POST[STUDENT_ID];
		return $model->deleteStudent($studentId);
	}
	
	public function importStudentsFromFile()
	{
		$file = $_FILES[FILENAME]['tmp_name'];
// 		return json_encode($file);
		$model = new UserService();
		return $model->importStudentsFromFile($file);
	}
	
	public function importStudentsFromLastCourse()
	{
		$model = new UserService();
		return $model->importStudentsFromLastCourse();
	}
}
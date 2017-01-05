<?php
namespace tfg\presentation\model;

final class HeaderViewModel extends MainViewModel {
	
	private $logged;
	private $teacher;
	private $course;
	private $trimestre;
	private $classroom;
	private $user;
	private $school;
	
	public function __construct()
	{
		parent::__construct();
		$this->logged = false;
		$this->teacher = null;
		$this->trimestre = null;
		$this->classroom = null;
		$this->initHeader();
	}
	
	public function output()
	{
		$data = new \stdClass;
		if ($this->logged)
		{
			$data->user = $this->teacher->name . " " . $this->teacher->surnames;
			$data->school = "Curs: " . $this->course->course;
// 			if ($this->classroom != null) $data->school .= "| Aula: " . $this->classroom->name;
			$data->school .= " | Trimestre: " . $this->trimestre->trimestre;
			$data->menus = $this->getMenuItems();
		}
		return $data;
	}
	
	private function initHeader()
	{
		$userModel = new UserViewModel();
		$schoolModel = new SchoolViewModel();
		$teacherId = $this->getSessionVar(USER_ID);
		$this->logged = $teacherId != false;
		if ($this->logged) $this->teacher = $userModel->getTeacher($teacherId);
		$classroomId = $this->getSessionVar(CLASSROOM_ID);
		if ($classroomId) $this->classroom = $schoolModel->getClassroom($classroomId);
		$this->course = $schoolModel->getActiveCourse();
		$this->trimestre = $schoolModel->getActiveTrimestre();
	}
	
	private function getMenuItems():array
	{
		$arrMenus = array();
		if ($this->logged) {
			$teacherId = $this->getSessionVar(USER_ID);
			$model = new \tfg\service\UserService();
			$menus = $model->getMenuItems($teacherId);
			foreach ($menus as $name=>$function) {
				array_push($arrMenus, array("name"=>$name, "function"=>$function));
			}
		}
		return $arrMenus;
	}
}
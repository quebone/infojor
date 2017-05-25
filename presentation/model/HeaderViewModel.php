<?php
namespace infojor\presentation\model;

use infojor\service\UserService;

final class HeaderViewModel extends MainViewModel {
	
	private $logged;
	private $teacher;
	private $course;
	private $trimestre;
	private $classroom;
	private $user;
	private $school;
	private $availableMenus;
	
	public function __construct($availableMenus = null)
	{
		parent::__construct();
		$this->logged = false;
		$this->teacher = null;
		$this->trimestre = null;
		$this->classroom = null;
		$this->availableMenus = $availableMenus;
		$this->initHeader();
	}
	
	/**
	 * Assigna les dades corresponents a la capçalera
	 */
	public function output():array
	{
		$data = array();
		if ($this->logged)
		{
			$data['user'] = $this->teacher['name'] . " " . $this->teacher['surnames'];
			$data['school'] = "Curs: " . $this->course->course;
			$data['school'] .= " | Trimestre: " . $this->trimestre['number'];
			$data['menus'] = $this->getMenus();
			if ($this->availableMenus != null) {
				foreach ($data['menus'] as $key => $value) {
					if (!in_array($key, $this->availableMenus)) unset($data['menus'][$key]);
				}
			}
			usort($data['menus'], function ($a, $b) {
				return ($a['order'] == $b['order']) ? 0 : (($a['order'] < $b['order']) ? -1 : 1);
			});
			}
		return $data;
	}
	
	/**
	 * Recupera les dades necessàries per la capçalera
	 */
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
			$model = new UserService();
			$menus = $model->getMenuItems($teacherId);
			foreach ($menus as $menu) {
				array_push($arrMenus, $menu->toArray());
			}
		}
		return $arrMenus;
	}
	
	private function getMenus():array
	{
		$menus = array();
		if ($this->logged) {
			$teacherId = $this->getSessionVar(USER_ID);
			$teacher = $this->dao->getById("Teacher", $teacherId);
			foreach ($teacher->getMenus() as $menu) {
				$menus[$menu->getId()] = $menu->toArray();
			}
		}
		return $menus;
	}
}
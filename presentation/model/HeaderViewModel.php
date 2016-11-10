<?php
namespace Infojor\Presentation\Model;

final class HeaderViewModel extends ViewModel {
	
	private $logged;
	private $teacher;
	private $course;
	private $trimestre;
	private $classroom;
	private $user;
	private $school;
	private $userViewModel;
	private $schoolViewModel;
	
	public function __construct($model = null, \Doctrine\ORM\EntityManager $entityManager = null)
	{
		parent::__construct($model, $entityManager);
		$this->userViewModel = new UserViewModel(null, $entityManager);
		$this->schoolViewModel = new SchoolViewModel(null, $entityManager);
		$this->logged = false;
		$this->teacher = null;
		$this->trimestre = null;
		$this->classroom = null;
		$this->setVars();
	}
	
	public function output()
	{
		$data = new \stdClass;
		if ($this->logged)
		{
			$data->user = $this->teacher->name . " " . $this->teacher->surnames;
			$data->school = "Curs: " . $this->course->course;
			if ($this->classroom != null) $data->school .= "| Aula: " . $this->classroom->name;
			$data->school .= " | Trimestre: " . $this->trimestre->trimestre;
		}
		return $data;
	}
	
	private function getSessionVar($sessionVar)
	{
		if (isset($_SESSION[$sessionVar])) return $_SESSION[$sessionVar];
		return false;
	}

	private function setVars()
	{
		$teacherId = $this->getSessionVar('userid');
		$this->logged = $teacherId != false;
		if ($teacherId) $this->teacher = $this->userViewModel->getTeacher($teacherId);
		$classroomId = $this->getSessionVar('classroom');
		if ($classroomId) $this->classroom = $this->schoolViewModel->getClassroom($classroomId);
		$this->course = $this->schoolViewModel->getActiveCourse();
		$this->trimestre = $this->schoolViewModel->getActiveTrimestre();
	}
}
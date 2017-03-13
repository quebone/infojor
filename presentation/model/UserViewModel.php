<?php
namespace infojor\presentation\model;

use infojor\service\Entities\Teacher;
use infojor\service\UserService;

final class UserViewModel extends MainViewModel {

	/**
	 * Variable que conté informació de les 3 seccions on pot estar assignat un mestre
	 */
	private $sections = array(
		array(
			'name'=>'Tutorings',
			'locale'=>'Tutories',
			'contents'=>'Classroom'
		),
		array(
			'name'=>'Specialities',
			'locale'=>'Especialitats',
			'contents'=>'Area'
		),
		array(
			'name'=>'Reinforcings',
			'locale'=>'Reforç individual',
			'contents'=>'ReinforceClassroom'
		)
	);
	
	public function __construct($model = null) {
		if ($model == null) $model = new UserService();
		parent::__construct($model);
	}
	
	/**
	 * Retorna l'id d'usuari si aquest existeix, 0 en cas contrari
	 */
	public function login($username, $password) {
		$model = new UserService();
		$teacher = $model->login($username, $password);
		if ($teacher != null && $teacher->getActive() == true) {
			$id = $teacher->getId();
			session_write_close();
			session_start();
			$_SESSION[USER_ID] = $id;
			return $id;
		}
		return 0;
	}
	
	public function getTeacher($id)
	{
		$data = array();
		if ($id == null) {
			$teacher = new Teacher("", "");
			$teacher->setActive(true);
		} else {
			$teacher = $this->dao->getById("Teacher", $id);
		}
		$data['id'] = $id;
		$data['name'] = $teacher->getName();
		$data['surnames'] = $teacher->getSurnames();
		$data['email'] = $teacher->getEmail();
		$data['phone'] = $teacher->getPhone();
		$data['username'] = $teacher->getUsername();
		$data['password'] = $teacher->getPassword();
		$data['isAdmin'] = $teacher->isAdmin();
		$data['isActive'] = $teacher->getActive();
		return $data;
	}
	
	public function getStudent($id)
	{
		$student = $this->dao->getById("Student", $id);
		$this->data->name = $student->getName();
		$this->data->surnames = $student->getSurnames();
		return $this->data;
	}
	
	/**
	 * Omple cadascuna de les seccions amb les dades corresponents
	 */
	private function getCurrentSection($teacherId, $main, $mapped)
	{
		$mainFunction = "getCurrent" . $main;
		$mappedFunction = "get" . $mapped;
		$items = $this->model->{$mainFunction}($teacherId);
		$data = array();
		foreach ($items as $item) {
			$data[$item->{$mappedFunction}()->getId()] = new \stdClass;
			$data[$item->{$mappedFunction}()->getId()]->name = $item->{$mappedFunction}()->getName();
			$data[$item->{$mappedFunction}()->getId()]->id = $item->{$mappedFunction}()->getId();
			$data[$item->{$mappedFunction}()->getId()]->link = $item->{$mappedFunction}()->getId();
		}
		return $data;
	}
	
	/**
	 * Crea l'estructura d'una secció
	 */
	private function createSection($teacherId, $section)
	{
		$section = array(
			'name'=>strtolower($section['name']),
			'locale'=>$section['locale'],
			'items'=>$this->getCurrentSection($teacherId, $section['name'], $section['contents']));
		return $section;
	}
	
	/**
	 * Retorna les dades de les seccions assignades a un mestre, en cas que existeixin 
	 */
	public function getCurrentSections($teacherId)
	{
		foreach ($this->sections as $section) {
			$newSection = $this->createSection($teacherId, $section);
			if (count($newSection['items']) > 0) {
				$sections[] = $newSection;
			}
		}
		return $sections;
	}
	
	/**
	 * Retorna una imatge assignada a una persona. Si no en té, retorna la imatge per defecte
	 */
	public function getThumbnail($personId)
	{
		$person = $this->dao->getById("Person", $personId);
		$thumbnail = $person->getThumbnail();
		$fileName = THUMBNAILDIR . $thumbnail;
		$type = pathinfo($fileName, PATHINFO_EXTENSION);
		if (!file_exists($fileName)) {
			$fileName = THUMBNAILDIR . AVATAR;
		}
		$data = file_get_contents($fileName);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
	
	/**
	 * Retorna tots els usuaris registrats al sistema 
	 */
	public function listAllTeachers():array
	{
		$data = array();
		$teachers = $this->dao->getByFilter("Teacher", array(), ['surnames'=>'ASC']);
		foreach ($teachers as $teacher) {
			array_push($data, $this->getTeacher($teacher->getId()));
		}
		return $data;
	}
	
	/**
	 * Elimina un mestre de la llista
	 */
	public function removeTeacher($teachers, $teacherId):array
	{
		for ($i=0; $i<count($teachers); $i++ ) {
			if ($teachers[$i]['id'] == $teacherId) {
				unset($teachers[$i]);
				return $teachers;
			}
		}
		return $teachers;
	}
}
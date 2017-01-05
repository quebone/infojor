<?php
namespace tfg\presentation\model;

use tfg\service\Entities\Teacher;
use tfg\service\UserService;

final class UserViewModel extends MainViewModel implements IUserViewModel {

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
	
	public function login($username, $password) {
		$teacher = $this->model->login($username, $password);
		if ($teacher != null) {
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
		if ($id == null) {
			$teacher = new Teacher("", "");
		} else {
			$teacher = $this->model->getTeacher($id);
		}
		$this->data->id = $id;
		$this->data->name = $teacher->getName();
		$this->data->surnames = $teacher->getSurnames();
		$this->data->email = $teacher->getEmail();
		$this->data->phone = $teacher->getPhone();
		$this->data->username = $teacher->getUsername();
		$this->data->password = $teacher->getPassword();
		$this->data->isAdmin = $teacher->isAdmin();
		return $this->data;
	}
	
	public function getStudent($id)
	{
		$model = new \tfg\service\UserService();
		$student = $model->getStudent($id);
		$this->data->name = $student->getName();
		$this->data->surnames = $student->getSurnames();
		return $this->data;
	}
	
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
	
	private function createSection($teacherId, $section)
	{
		$section = array(
			'name'=>strtolower($section['name']),
			'locale'=>$section['locale'],
			'items'=>$this->getCurrentSection($teacherId, $section['name'], $section['contents']));
		return $section;
	}
		
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
	
	public function getThumbnail($personId)
	{
		$model = new \tfg\service\UserService();
		$person = $model->getPerson($personId);
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
	
	public function listAllTeachers():array
	{
		$teachersData = array();
		$model = new \tfg\service\UserService();
		$teachers = $model->getAllTeachers();
		foreach ($teachers as $teacher) {
			array_push($teachersData, array(
				"id"=>$teacher->getId(),
				"name"=>$teacher->getName(),
				"surnames"=>$teacher->getSurnames(),
			));
		}
		return $teachersData;
	}
	
	public function importStudentsFromFile() {
		//TODO
	}

	public function importStudentsFromLastCourse() {
		//TODO
	}

	public function importStudentsFromLastTrimestre() {
		//TODO
	}
}
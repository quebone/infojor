<?php
namespace tfg\presentation\model;

use tfg\service\Entities\Cycle;

final class SchoolViewModel extends MainViewModel implements ISchoolViewModel {
	
	public function __construct() {
		parent::__construct();
		$this->model = new \tfg\service\SchoolService();
	}
	
	public function getActiveCourse()
	{
		$this->data->course = $this->model->getActiveCourse()->getYear(); 
		return $this->data;
	}
	
	public function getActiveTrimestre()
	{
		$this->data->trimestre = $this->model->getActiveTrimestre()->getNumber(); 
		return $this->data;
	}
	
	public function getCurrentClassroomStudents($classroomId):array {
		$this->data->students = array();
		$students = $this->model->getCurrentClassroomStudents($classroomId);
		foreach ($students as $student) {
			$id = $student->getId();
			$this->data->students[$id] = new \stdClass;
			$this->data->students[$id]->id = $student->getId();
			$this->data->students[$id]->name = $student->getName(); 
			$this->data->students[$id]->surnames = $student->getSurnames();
			}
		$firstStudent = reset($this->data->students);
		$firstStudent->selected = true;
		return $this->data->students;
	}
	
	public function getClassroom($classroomId):array
	{
		$classroom = $this->model->getClassroom($classroomId);
		$data['id'] = $classroom->getId();
		$data['name'] = $classroom->getName();
		return $data;
	}
	
	public function getClassrooms():array
	{
		$data = array();
		$classrooms = $this->model->getClassrooms();
		foreach ($classrooms as $classroom) {
			array_push($data, $this->getClassroom($classroom->getId()));
		}
		return $data;
	}
	
	public function getArea($areaId):array
	{
		$data = array();
		$area = $this->model->getArea($areaId);
		if ($area != null) {
			$data['id'] = $area->getId();
			$data['name'] = $area->getName();
		}
		return $data;
	}
	
	public function getReinforceClassroom($reinforceId):array
	{
		$data = array();
		$reinforceClassroom = $this->model->getReinforceClassroom($reinforceId);
		if ($reinforceClassroom != null) {
			$data['id'] = $reinforceClassroom->getId();
			$data['name'] = $reinforceClassroom->getName();
		}
		return $data;
	}
	
	public function getScopes($classromId):array
	{
		$data = array();
		$scopes = $this->model->getClassroomScopes($classromId);
		foreach ($scopes as $scope) {
			$id = $scope->getId();
			$data[$id]['id'] = $id;
			$data[$id]['name'] = $scope->getName();
			$data[$id]['description'] = $scope->getDescription();
		}
		return $data;
	}
	
	public function getScopeAreas($scopeId, $areaId = null):array
	{
		$data = array();
		$areas = $this->model->getScopeAreas($scopeId);
		if (count($areas) != null) {
			if ($areaId == null) {
				foreach ($areas as $area) {
					if (!$area->isSpeciality()) {
						$id = $area->getId();
						$data[$id]['id'] = $id;
						$data[$id]['name'] = $area->getName();
					}
				}
			} else {
				foreach ($areas as $area) {
					if ($area->getId() == $areaId) {
						$id = $area->getId();
						$data[$id]['id'] = $id;
						$data[$id]['name'] = $area->getName();
					}
				}
			}
		}
		return $data;
	}
	
	public function getAreaDimensions($areaId, Cycle $cycle = null):array
	{
		$data = array();
		$dimensions = $this->model->getAreaDimensions($areaId, $cycle);
		if (count($dimensions) > 0) {
			foreach ($dimensions as $dimension) {
				$id = $dimension->getId();
				$data[$id]['id'] = $dimension->getId();
				$data[$id]['name'] = $dimension->getName();
				$data[$id]['description'] = $dimension->getDescription();
			}
		}
		return $data;
	}
	
	public function listAllCourses()
	{
		$coursesData = array();
		$courses = $this->model->getCourses();
		$ac = $this->model->getActiveCourse();
		foreach ($courses as $course) {
			array_push($coursesData, array(
					"id" => $course->getId(),
					"year" => $course->getYear(),
					"active" => ($course == $ac ? true : false)));
		}
		return $coursesData;
	}
	
	public function listAllTutorings()
	{
		//TODO
	}

	public function listAllSpecialities()
	{
		//TODO
	}

	public function listAllReinforcings()
	{
		//TODO
	}
}
<?php
namespace Infojor\Presentation\Model;

use Infojor\Service\Entities\Cycle;

final class SchoolViewModel extends ViewModel {
	
	public function __construct($model = null, \Doctrine\ORM\EntityManager $entityManager = null) {
		if ($model == null) $model = new \Infojor\Service\SchoolService($entityManager);
		parent::__construct($model, $entityManager);
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
	
	public function getPerson($id)
	{
		$person = $this->model->getPerson($id);
		$this->data->name = $person->getName();
		$this->data->surnames = $person->getSurnames();
		return $this->data;
	}
	
	public function getCurrentClassroomStudents($classroomId) {
		$this->data->students = array();
		$students = $this->model->getCurrentClassroomStudents($classroomId);
		foreach ($students as $student) {
			$id = $student->getId();
			$completeName = $student->getSurnames() . ", " . $student->getName();
			$this->data->students[$id] = new \stdClass; 
			$this->data->students[$id]->name = $completeName; 
		}
		$firstStudent = reset($this->data->students);
		$firstStudent->selected = true;
		return $this->data;
	}
	
	public function getClassroom($classroomId):array
	{
		$classroom = $this->model->getClassroom($classroomId);
		$data['id'] = $classroom->getId();
		$data['name'] = $classroom->getName();
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
}
<?php
namespace infojor\presentation\model;

use infojor\service\Entities\Cycle;
use infojor\presentation\view\TplEngine;
use infojor\service\SchoolService;

final class SchoolViewModel extends MainViewModel {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getActiveCourse()
	{
		$this->data->course = $this->dao->getActiveCourse()->getYear(); 
		return $this->data;
	}
	
	public function getActiveTrimestre()
	{
		return $this->dao->getActiveTrimestre()->toArray();
	}
	
	public function getPreviousTrimestres():array
	{
		$model = new SchoolService();
		$trimestres = $model->getPreviousTrimestres();
		$data = array();
		foreach ($trimestres as $trimestre) {
			array_push($data, $trimestre->toArray());
		}
		return $data;
	}
	
	/**
	 * Retorna els alumnes actuals d'una classe i activa el primer alumne de la llista
	 */
	public function getCurrentClassroomStudents($classroomId):array {
		$this->data->students = array();
		$model = new SchoolService();
		$students = $model->getCurrentClassroomStudents($classroomId);
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
		$classroom = $this->dao->getById("Classroom", $classroomId);
		return $classroom->toArray();
	}
	
	public function getClassrooms($degrees = null):array
	{
		$data = array();
		$classrooms = $this->dao->getByFilter("Classroom");
		foreach ($classrooms as $classroom) {
			if ($degrees == null || in_array($classroom->getLevel()->getCycle()->getDegree()->getId(), $degrees))
				array_push($data, $classroom->toArray());
		}
		return $data;
	}
	
	public function getArea($areaId):array
	{
		$data = array();
		$area = $this->dao->getById("Area", $areaId);
		if ($area != null) {
			$data['id'] = $area->getId();
			$data['name'] = $area->getName();
		}
		return $data;
	}
	
	public function getAreas():array
	{
		$data = array();
		$areas = $this->dao->getByFilter("Area");
		foreach ($areas as $area) {
			array_push($data, $this->getArea($area->getId()));
		}
		return $data;
	}
	
	public function getDegreeAreas($degreeId):array
	{
		$data = array();
		$areas = array();
		$model = new SchoolService();
		foreach ($model->getDegreeAreas($degreeId) as $area) {
			$data['id'] = $area->getId();
			$data['name'] = $area->getName();
			array_push($areas, $data);
		}
		return $areas;
	}
	
	public function getReinforceClassroom($reinforceId):array
	{
		$data = array();
		$reinforceClassroom = $this->dao->getById("ReinforceClassroom", $reinforceId);
		if ($reinforceClassroom != null) {
			$data['id'] = $reinforceClassroom->getId();
			$data['name'] = $reinforceClassroom->getName();
		}
		return $data;
	}
	
	public function getReinforceClassrooms():array
	{
		$data = array();
		$classrooms = $this->dao->getByFilter("ReinforceClassroom");
		foreach ($classrooms as $classroom) {
			array_push($data, $this->getReinforceClassroom($classroom->getId()));
		}
		return $data;
	}
	
	public function getDegree($degreeId):array
	{
		$data = array();
		$degree = $this->dao->getById("Degree", $degreeId);
		if ($degree != null) {
			$data['id'] = $degree->getId();
			$data['name'] = $degree->getName();
		}
		return $data;
	}
	
	public function getDegrees():array
	{
		$data = array();
		$degrees = $this->dao->getByFilter("Degree");
		foreach ($degrees as $degree) {
			array_push($data, $this->getDegree($degree->getId()));
		}
		return $data;
	}
	
	private function getCycle(Cycle $cycle):array
	{
		$data = array();
		$data['id'] = $cycle->getId();
		$data['name'] = $cycle->getName();
		return $data;
	}
	
	public function getCycles($degreeId):array
	{
		$data = array();
		$degree = $this->dao->getById("Degree", $degreeId);
		$cycles = $degree->getCycles();
		foreach ($cycles as $cycle) {
			array_push($data, $this->getCycle($cycle));
		}
		return $data;
	}
	
	private function getDimensionCycles($dimensionId)
	{
		$data = array();
		$dimension = $this->dao->getById("Dimension", $dimensionId);
		$cycles = $dimension->getCycles();
		foreach ($cycles as $cycle) {
			array_push($data, $this->getCycle($cycle));
		}
		return $data;
	}
	
	public function getScopes($degreeId):array
	{
		$data = array();
		$model = new SchoolService();
		$scopes = $model->getDegreeScopes($degreeId);
		foreach ($scopes as $scope) {
			$id = $scope->getId();
			$data[$id]['id'] = $id;
			$data[$id]['name'] = $scope->getName();
			$data[$id]['description'] = $scope->getDescription();
		}
		return $data;
	}
	
	/**
	 * Retorna les àrees assignades a un àmbit
	 */
	public function getScopeAreas($scopeId, $areaId = null):array
	{
		$data = array();
		$model = new SchoolService();
		$areas = $model->getScopeAreas($scopeId);
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
	
	/**
	 * Retorna les dimensions associades a una àrea i un cicle
	 */
	public function getAreaDimensions($areaId, Cycle $cycle = null, $onlyActive = true):array
	{
		$data = array();
		$model = new SchoolService();
		$dimensions = $model->getAreaDimensions($areaId, $cycle, $onlyActive);
		if (count($dimensions) > 0) {
			foreach ($dimensions as $dimension) {
				$id = $dimension->getId();
				$data[$id]['id'] = $dimension->getId();
				$data[$id]['name'] = $dimension->getName();
				$data[$id]['description'] = $dimension->getDescription();
				$data[$id]['active'] = $dimension->isActive();
			}
		}
		return $data;
	}
	
	public function listAllCourses()
	{
		$coursesData = array();
		$courses = $this->dao->getByFilter("Course");
		$ac = $this->dao->getActiveCourse();
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
		$data = array();
		$model = new SchoolService();
		$tutorings = $model->getCurrentTutorings();
		foreach ($tutorings as $tutoring) {
			foreach ($tutoring['tutors'] as $tutor) {
				array_push($data, array(
					'classroomId' => $tutoring['classroom']->getId(),
					'classroom' => $tutoring['classroom']->getName(),
					'id' => $tutor->getTeacher()->getId(),
					'name' => $tutor->getTeacher()->getName(),
					'surnames' => $tutor->getTeacher()->getSurnames()
				));
			}
		}
		return $data;
	}

	public function listAllSpecialities()
	{
		$data = array();
		$model = new SchoolService();
		$specialities = $model->getCurrentSpecialities();
		foreach ($specialities as $speciality) {
			foreach ($speciality['specialists'] as $specialist) {
				array_push($data, array(
						'areaId' => $speciality['area']->getId(),
						'area' => $speciality['area']->getName(),
						'id' => $specialist->getTeacher()->getId(),
						'name' => $specialist->getTeacher()->getName(),
						'surnames' => $specialist->getTeacher()->getSurnames()
				));
			}
		}
		return $data;
	}

	public function listAllReinforcings()
	{
		$data = array();
		$model = new SchoolService();
		$reinforcings = $model->getCurrentReinforcings();
		foreach ($reinforcings as $reinforcing) {
			foreach ($reinforcing['reinforcers'] as $reinforcer) {
				array_push($data, array(
						'classroomId' => $reinforcing['classroom']->getId(),
						'classroom' => $reinforcing['classroom']->getName(),
						'id' => $reinforcer->getTeacher()->getId(),
						'name' => $reinforcer->getTeacher()->getName(),
						'surnames' => $reinforcer->getTeacher()->getSurnames()
				));
			}
		}
		return $data;
	}
	
	public function listAllDimensions($cycleId)
	{
		$cycle = $this->dao->getById("Cycle", $cycleId);
		$degree = $cycle->getDegree();
		$scopes = $this->getScopes($degree->getId());
		foreach ($scopes as $scope) {
			$areas = $this->getScopeAreas($scope['id']);
			foreach ($areas as $area) {
				$dimensions = $this->getAreaDimensions($area['id'], null, false);
				foreach ($dimensions as $dimension) {
					$allCycles = $this->getCycles($degree->getId());
					$cycles = $this->getDimensionCycles($dimension['id']);
					$engine = new TplEngine();
					$dimensions[$dimension['id']]['cycles'] = $engine->arrayToCheckbox($allCycles, $cycles, 
							"onchange='updateDimension(" . $dimension['id'] . ")'");
				}
				$areas[$area['id']]['dimensions'] = $dimensions;
			}
			$scopes[$scope['id']]['areas'] = $areas;
		}
		return $scopes;
	}
}
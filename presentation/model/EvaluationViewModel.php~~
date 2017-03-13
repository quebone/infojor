<?php
namespace tfg\presentation\model;

final class EvaluationViewModel extends MainViewModel {
	
	public function __construct() {
		parent::__construct();
	}

	private function getPartialEvaluationDescriptions():array
	{
		$model = new \tfg\service\EvaluationService();
		$peds = $model->getPartialEvaluationDescriptions();
		foreach ($peds as $ped) {
			$id = $ped->getId();
			$data[$id]['id'] = $ped->getId();
			$data[$id]['mark'] = $ped->getMark();
			$data[$id]['description'] = $ped->getDescription();
		}
		return $data;
	}

	private function getGlobalEvaluationDescriptions():array
	{
		$model = new \tfg\service\EvaluationService();
		$geds = $model->getGlobalEvaluationDescriptions();
		foreach ($geds as $ged) {
			$id = $ged->getId();
			$data[$id]['id'] = $ged->getId();
			$data[$id]['mark'] = $ged->getMark();
			$data[$id]['description'] = $ged->getDescription();
		}
		return $data;
	}

	private function getDimensionEvaluation($studentId, $dimensionId)
	{
		$model = new \tfg\service\EvaluationService();
		$pe = $model->getDimensionEvaluation($studentId, $dimensionId);
		if ($pe == null) return null;
		return $pe->getMark();
	}
	
	private function getAreaEvaluation($studentId, $areaId)
	{
		$model = new \tfg\service\EvaluationService();
		$ge = $model->getAreaEvaluation($studentId, $areaId);
		if ($ge == null) return null;
		return $ge->getMark();
	}
	
	private function getScopeEvaluation($studentId, $scopeId)
	{
		$model = new \tfg\service\EvaluationService();
		$ge = $model->getScopeEvaluation($studentId, $scopeId);
		if ($ge == null) return null;
		return $ge->getMark();
	}
	
	/**
	 * Munta tota l'estructura dels àmbits
	 * També es crida per crear les dades dels informes
	 */
	public function getScopesData($studentId, $classroom, $areaId)
	{
		$cycle = $classroom->getLevel()->getCycle();
		$schoolViewModel = new SchoolViewModel();
		$scopes = $schoolViewModel->getScopes($cycle->getDegree()->getId());
		$data = $scopes;
		foreach ($scopes as $scope) {
			$areas = $schoolViewModel->getScopeAreas($scope['id'], $areaId);
			$data[$scope['id']]['areas'] = $areas;
			foreach ($areas as $area) {
				$dimensions = $schoolViewModel->getAreaDimensions($area['id'], $cycle);
				$data[$scope['id']]['areas'][$area['id']]['dimensions'] = $dimensions;
				if ($dimensions != null) {
					foreach ($dimensions as $dimension) {
						$pe = $this->getDimensionEvaluation($studentId, $dimension['id']);
						$data[$scope['id']]['areas'][$area['id']]['dimensions'][$dimension['id']]['mark'] = $pe;
					}
				}
				$ge = $this->getAreaEvaluation($studentId, $area['id']);
				$data[$scope['id']]['areas'][$area['id']]['mark'] = $ge;
			}
			// 				$ge = $this->getScopeEvaluation($studentId, $scope['id']);
			// 				$evaluation['scopes'][$scope['id']]['ge'] = $ge;
		}
		// eliminem els àmbits sense àrea
		foreach ($data as $scope) {
			if (count($scope['areas']) == 0) {
				unset($data[$scope['id']]);
			}
		}
		return $data;
	}
	
	/**
	 * Recupera totes les qualificacions actuals d'un alumne
	 */
	public function getEvaluations($studentId, $areaId, $reinforceId, $includeSpecialities):array
	{
		$userModel = new \tfg\service\UserService($this->entityManager);
		$schoolModel = new \tfg\service\SchoolService($this->entityManager);
		$schoolViewModel = new SchoolViewModel(null, $this->entityManager);
		$course = $schoolModel->getActiveCourse();
		$trimestre = $schoolModel->getActiveTrimestre();
		$student = $userModel->getStudent($studentId);
		$classroom = $student->getClassroom($course, $trimestre);
		$degree = $classroom->getLevel()->getCycle()->getDegree();
		$evaluation['peds'] = $this->getPartialEvaluationDescriptions();
		$evaluation['geds'] = $this->getGlobalEvaluationDescriptions();
		$evaluation['classroom'] = $schoolViewModel->getClassroom($classroom->getId());
		$evaluation['student']['name'] = $student->getName() . " " . $student->getSurnames();
		
		//les qualificacions de reforç no han de sortir al formulari general d'entrada
		if ($reinforceId == null) {
			$evaluation['scopes'] = $this->getScopesData($studentId, $classroom, $areaId);
			$observation = $student->getCourseObservation($course, $trimestre);
			$observationText = $observation != null ? $observation->getText() : '';
			$evaluation['observation'] = trim(stripcslashes($observationText),'"');

		//les qualificacions de reforç surten a part de les generals
		} else {
			$reinforceClassroom = $schoolModel->getReinforceClassroom($reinforceId);
			$evaluation['reinforce'] = $schoolViewModel->getReinforceClassroom($reinforceId);
			$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
			if ($observation == null) {
				$evaluation['reinforce']['observation']['id'] = null;
				$evaluation['reinforce']['observation']['text'] = '';
			} else {
				$evaluation['reinforce']['observation']['id'] = $observation->getId();
				$evaluation['reinforce']['observation']['text'] = $observation->getText();
			}
		}
		return $evaluation;
	}
}
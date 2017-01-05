<?php
namespace tfg\presentation\model;

final class EvaluationViewModel extends MainViewModel implements IEvaluationViewModel {
	
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
	
	public function getEvaluations($studentId, $areaId, $reinforceId, $includeSpecialities):array
	{
		$userModel = new \tfg\service\UserService($this->entityManager);
		$schoolModel = new \tfg\service\SchoolService($this->entityManager);
		$schoolViewModel = new SchoolViewModel(null, $this->entityManager);
		$course = $schoolModel->getActiveCourse();
		$trimestre = $schoolModel->getActiveTrimestre();
		$student = $userModel->getStudent($studentId);
		$classroom = $student->getClassroom($course, $trimestre);
		$cycle = $classroom->getLevel()->getCycle();
		$evaluation['peds'] = $this->getPartialEvaluationDescriptions();
		$evaluation['geds'] = $this->getGlobalEvaluationDescriptions();
		$evaluation['classroom'] = $schoolViewModel->getClassroom($classroom->getId());
		$evaluation['student']['name'] = $student->getName() . " " . $student->getSurnames();
		if ($reinforceId == null) {
			$scopes = $schoolViewModel->getScopes($classroom->getId());
			$evaluation['scopes'] = $scopes;
			foreach ($scopes as $scope) {
				$areas = $schoolViewModel->getScopeAreas($scope['id'], $areaId);
				$evaluation['scopes'][$scope['id']]['areas'] = $areas;
				foreach ($areas as $area) {
					$dimensions = $schoolViewModel->getAreaDimensions($area['id'], $cycle);
					$evaluation['scopes'][$scope['id']]['areas'][$area['id']]['dimensions'] = $dimensions;
					if ($dimensions != null) {
						foreach ($dimensions as $dimension) {
							$pe = $this->getDimensionEvaluation($studentId, $dimension['id']);
							$evaluation['scopes'][$scope['id']]['areas'][$area['id']]['dimensions'][$dimension['id']]['pe'] = $pe;
						}
					}
					$ge = $this->getAreaEvaluation($studentId, $area['id']);
					$evaluation['scopes'][$scope['id']]['areas'][$area['id']]['ge'] = $ge;
				}
				$ge = $this->getScopeEvaluation($studentId, $scope['id']);
				$evaluation['scopes'][$scope['id']]['ge'] = $ge;
			}
			// eliminem els àmbits sense àrea
			foreach ($evaluation['scopes'] as $scope) {
				if (count($scope['areas']) == 0) {
					unset($evaluation['scopes'][$scope['id']]);
				}
			}
			$observation = $student->getCourseObservation($course, $trimestre);
			$observationText = $observation != null ? $observation->getText() : '';
			$evaluation['observation'] = $observationText;
		} else {
			$reinforceClassroom = $schoolModel->getReinforceClassroom($reinforceId);
			$evaluation['reinforcing'] = $schoolViewModel->getReinforceClassroom($reinforceId);
			$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
			if ($observation == null) {
				$evaluation['reinforcing']['observation']['id'] = null;
				$evaluation['reinforcing']['observation']['text'] = '';
			} else {
				$evaluation['reinforcing']['observation']['id'] = $observation->getId();
				$evaluation['reinforcing']['observation']['text'] = $observation->getText();
			}
		}
		return $evaluation;
	}
}
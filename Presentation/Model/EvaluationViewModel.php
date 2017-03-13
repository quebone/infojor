<?php
namespace Infojor\Presentation\Model;

final class EvaluationViewModel extends ViewModel {
	
	public function __construct($model = null, \Doctrine\ORM\EntityManager $entityManager = null) {
		if ($model == null) $model = new \Infojor\Service\EvaluationService($entityManager);
		parent::__construct($model, $entityManager);
	}

	private function getPartialEvaluationDescriptions():array
	{
		$peds = $this->model->getPartialEvaluationDescriptions();
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
		$geds = $this->model->getGlobalEvaluationDescriptions();
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
		$pe = $this->model->getDimensionEvaluation($studentId, $dimensionId);
		if ($pe == null) return null;
		return $pe->getMark();
	}
	
	private function getAreaEvaluation($studentId, $areaId)
	{
		$ge = $this->model->getAreaEvaluation($studentId, $areaId);
		if ($ge == null) return null;
		return $ge->getMark();
	}
	
	private function getScopeEvaluation($studentId, $scopeId)
	{
		$ge = $this->model->getScopeEvaluation($studentId, $scopeId);
		if ($ge == null) return null;
		return $ge->getMark();
	}
	
	public function getEvaluations($studentId, $classroomId, $areaId, $reinforceId, $includeSpecialities):array
	{
		$userModel = new \Infojor\Service\UserService($this->entityManager);
		$schoolModel = new \Infojor\Service\SchoolService($this->entityManager);
		$schoolViewModel = new SchoolViewModel(null, $this->entityManager);
		$student = $userModel->getStudent($studentId);
		$cycle = $schoolModel->getClassroom($classroomId)->getLevel()->getCycle();
		$course = $schoolModel->getActiveCourse();
		$trimestre = $schoolModel->getActiveTrimestre();
		$evaluation['peds'] = $this->getPartialEvaluationDescriptions();
		$evaluation['geds'] = $this->getGlobalEvaluationDescriptions();
		$evaluation['classroom'] = $schoolViewModel->getClassroom($classroomId);
		$evaluation['area'] = $schoolViewModel->getArea($areaId);
		$evaluation['student']['name'] = $student->getName() . " " . $student->getSurnames();
		if ($reinforceId == null) {
			$scopes = $schoolViewModel->getScopes($classroomId);
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
// 			if ($areaId == null && $reinforceId == null) {
				$observation = $student->getCourseObservation($course, $trimestre);
				$observationText = $observation != null ? $observation->getText() : '';
				$evaluation['observation'] = $observationText;
// 			} else {
// 				$evaluation['observation'] = null;
// 			}
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
	
	public function setPartialEvaluation($studentId, $evaluationId, $markId)
	{
		return $this->model->setPartialEvaluation($studentId, $evaluationId, $markId);
	}

	public function setGlobalEvaluation($studentId, $evaluationId, $markId)
	{
		return $this->model->setGlobalEvaluation($studentId, $evaluationId, $markId);
	}
	
	public function setObservation($studentId, $text, $reinforceId=null) {
		return $this->model->setObservation($studentId, $text, $reinforceId);
	}
}
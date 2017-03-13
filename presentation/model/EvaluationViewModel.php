<?php
namespace infojor\presentation\model;

use infojor\service\DAO;
use infojor\service\EvaluationService;

final class EvaluationViewModel extends MainViewModel {
	
	public function __construct() {
		parent::__construct();
	}

	private function getPartialEvaluationDescriptions():array
	{
		$peds = $this->dao->getByFilter("PartialEvaluationDescription");
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
		$geds = $this->dao->getByFilter("GlobalEvaluationDescription");
		foreach ($geds as $ged) {
			$id = $ged->getId();
			$data[$id]['id'] = $ged->getId();
			$data[$id]['mark'] = $ged->getMark();
			$data[$id]['description'] = $ged->getDescription();
		}
		return $data;
	}

	private function getDimensionEvaluation($studentId, $dimensionId, $courseId=null, $trimestreId=null)
	{
		$model = new EvaluationService();
		$pe = $model->getDimensionEvaluation($studentId, $dimensionId, $courseId, $trimestreId);
		if ($pe == null) return null;
		return $pe->getMark();
	}
	
	private function getAreaEvaluation($studentId, $areaId, $courseId=null, $trimestreId=null)
	{
		$model = new EvaluationService();
		$ge = $model->getAreaEvaluation($studentId, $areaId, $courseId, $trimestreId);
		if ($ge == null) return null;
		return $ge->getMark();
	}
	
	private function getScopeEvaluation($studentId, $scopeId)
	{
		$model = new EvaluationService();
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
		$schoolModel = new SchoolViewModel();
		$scopes = $schoolModel->getScopes($cycle->getDegree()->getId());
		$data = $scopes;
		$dao = new DAO();
		$ac = $dao->getActiveCourse();
		$at = $dao->getActiveTrimestre();
		foreach ($scopes as $scope) {
			$areas = $schoolModel->getScopeAreas($scope['id'], $areaId);
			$data[$scope['id']]['areas'] = $areas;
			foreach ($areas as $area) {
				$dimensions = $schoolModel->getAreaDimensions($area['id'], $cycle);
				$data[$scope['id']]['areas'][$area['id']]['dimensions'] = $dimensions;
				if ($dimensions != null) {
					foreach ($dimensions as $dimension) {
						for ($i = 1; $i <= $at->getNumber(); $i++) {
							$pe = $this->getDimensionEvaluation($studentId, $dimension['id'], $ac->getId(), $i);
							$data[$scope['id']]['areas'][$area['id']]['dimensions'][$dimension['id']]['pes'][$i]['mark'] = $pe;
						}
					}
				}
				for ($i = 1; $i <= $at->getNumber(); $i++) {
					$ge = $this->getAreaEvaluation($studentId, $area['id'], $ac->getId(), $i);
					$data[$scope['id']]['areas'][$area['id']]['ges'][$i]['mark'] = $ge;
				}
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
		$schoolModel = new SchoolViewModel();
		$course = $this->dao->getActiveCourse();
		$trimestre = $this->dao->getActiveTrimestre();
		$student = $this->dao->getById("Student", $studentId);
		$classroom = $student->getClassroom($course, $trimestre);
		$degree = $classroom->getLevel()->getCycle()->getDegree();
		$evaluation['trimestre'] = $trimestre->getNumber();
		$evaluation['peds'] = $this->getPartialEvaluationDescriptions();
		$evaluation['geds'] = $this->getGlobalEvaluationDescriptions();
		$evaluation['classroom'] = $schoolModel->getClassroom($classroom->getId());
		$evaluation['student']['name'] = $student->getName() . " " . $student->getSurnames();
		$evaluation['previousTrimestres'] = $schoolModel->getPreviousTrimestres(); 
		
		//les qualificacions de reforç no han de sortir al formulari general d'entrada
		if ($reinforceId == null) {
			$evaluation['scopes'] = $this->getScopesData($studentId, $classroom, $areaId);
			$observation = $student->getCourseObservation($course, $trimestre);
			$observationText = $observation != null ? $observation->getText() : '';
			$evaluation['observation'] = trim(stripcslashes($observationText),'"');

		//les qualificacions de reforç surten a part de les generals
		} else {
			$reinforceClassroom = $this->dao->getById("ReinforceClassroom", $reinforceId);
			$evaluation['reinforce'] = $schoolModel->getReinforceClassroom($reinforceId);
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
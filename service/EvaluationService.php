<?php
namespace tfg\service;

use tfg\service\Entities\Observation;

final class EvaluationService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getPartialEvaluationDescriptions()
	{
		return  $this->entityManager->getRepository(
				'tfg\\service\\Entities\\PartialEvaluationDescription')->findAll();
	}
	
	public function getGlobalEvaluationDescriptions()
	{
		return  $this->entityManager->getRepository(
				'tfg\\service\\Entities\\GlobalEvaluationDescription')->findAll();
	}
	
	public function getPartialEvaluationDescription($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\PartialEvaluationDescription', $id);
	}
	
	public function getGlobalEvaluationDescription($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\GlobalEvaluationDescription', $id);
	}
	
	/**
	 * Retorna les qualificacions parcials assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getDimensionEvaluation($studentId, $dimensionId, $courseId=null, $trimestreId=null)
	{
		$userModel = new UserService($this->entityManager);
		$schoolModel = new SchoolService($this->entityManager);
		if ($courseId == null) {
			$course = $this->getActiveCourse();
		} else {
			$course = $this->getCourse($courseId);
		}
		if ($trimestreId == null) {
			$trimestre = $this->getActiveTrimestre();
		} else {
			$trimestre = $this->getTrimestre($trimestreId);
		}
		$student = $userModel->getStudent($studentId);
		$dimension = $schoolModel->getDimension($dimensionId);
		return $student->getDimensionEvaluation($dimension, $course, $trimestre);
	}
	
	/**
	 * Retorna les qualificacions d'àrea assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getAreaEvaluation($studentId, $areaId, $courseId=null, $trimestreId=null)
	{
		$userModel = new UserService($this->entityManager);
		$schoolModel = new SchoolService($this->entityManager);
		if ($courseId == null) {
			$course = $this->getActiveCourse();
		} else {
			$course = $this->getCourse($courseId);
		}
		if ($trimestreId == null) {
			$trimestre = $this->getActiveTrimestre();
		} else {
			$trimestre = $this->getTrimestre($trimestreId);
		}
		$student = $userModel->getStudent($studentId);
		$area = $schoolModel->getArea($areaId);
		return $student->getAreaEvaluation($area, $course, $trimestre);
	}
	
	/**
	 * Retorna les qualificacions d'àmbit assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getScopeEvaluation($studentId, $scopeId, $courseId=null, $trimestreId=null)
	{
		$userModel = new UserService($this->entityManager);
		$schoolModel = new SchoolService($this->entityManager);
		if ($courseId == null) {
			$course = $this->getActiveCourse();
		} else {
			$course = $this->getCourse($courseId);
		}
		if ($trimestreId == null) {
			$trimestre = $this->getActiveTrimestre();
		} else {
			$trimestre = $this->getTrimestre($trimestreId);
		}
		$student = $userModel->getStudent($studentId);
		$scope = $schoolModel->getScope($scopeId);
		return $student->getScopeEvaluation($scope, $course, $trimestre);
	}
	
	/**
	 * Assigna una qualificació parcial a un alumne
	 */
	public function setPartialEvaluation($studentId, $dimensionId, $markId)
	{
		$userModel = new UserService();
		$schoolModel = new SchoolService();
		$course = $this->getActiveCourse();
		$trimestre = $this->getActiveTrimestre();
		$dimension = $schoolModel->getDimension($dimensionId);
		$ed = $this->getPartialEvaluationDescription($markId);
		$student = $userModel->getStudent($studentId);
		$evaluation = $student->getDimensionEvaluation($dimension, $course, $trimestre);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createDimensionEvaluation($dimension, $course, $trimestre, $ed);
			$this->entityManager->persist($evaluation);
		} else { 
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->entityManager->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setPartialEvaluationDescription($ed);
				$this->entityManager->persist($evaluation);
			}
		}
		$this->entityManager->flush($evaluation);
	}

	/**
	 * Assigna una qualificació global a un alumne
	 */
	public function setGlobalEvaluation($studentId, $areaId, $markId)
	{
		$userModel = new UserService($this->entityManager);
		$schoolModel = new SchoolService($this->entityManager);
		$course = $this->getActiveCourse();
		$trimestre = $this->getActiveTrimestre();
		$area = $schoolModel->getArea($areaId);
		$ed = $this->getGlobalEvaluationDescription($markId);
		$student = $userModel->getStudent($studentId);
		$evaluation = $student->getAreaEvaluation($area, $course, $trimestre);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createAreaEvaluation($area, $course, $trimestre, $ed);
			$this->entityManager->persist($evaluation);
		} else {
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->entityManager->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setGlobalEvaluationDescription($ed);
				$this->entityManager->persist($evaluation);
			}
		}
		$this->entityManager->flush($evaluation);
	}
	
	/**
	 * Assigna una observació general o de reforç a un alumne
	 */
	public function setObservation($studentId, $text, $reinforceId = null)
	{
		$userModel = new UserService($this->entityManager);
		$schoolModel = new SchoolService($this->entityManager);
		$course = $this->getActiveCourse();
		$trimestre = $this->getActiveTrimestre();
		$student = $userModel->getStudent($studentId);
		$reinforceClassroom = $reinforceId ? $schoolModel->getReinforceClassroom($reinforceId) : null;
		$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
		if ($observation == null) {
			//create new observation
			$observation = $student->createObservation($text, $course, $trimestre, $reinforceClassroom);
			$this->entityManager->persist($observation);
		} else {
			if (strlen(trim($text, '"')) == 0) {
				//delete observation
				$this->entityManager->remove($observation);
			} else {
				//update observation
				$observation->setText($text);
				$this->entityManager->persist($observation);
			}
		}
		$this->entityManager->flush($observation);
	}
}
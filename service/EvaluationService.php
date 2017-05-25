<?php
namespace infojor\service;

use infojor\service\Entities\Observation;

final class EvaluationService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Retorna les qualificacions parcials assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getDimensionEvaluation($studentId, $dimensionId, $courseId=null, $trimestreId=null)
	{
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$student = $this->dao->getById("Student", $studentId);
		$dimension = $this->dao->getById("Dimension", $dimensionId);
		return $student->getDimensionEvaluation($dimension, $course, $trimestre);
	}
	
	/**
	 * Retorna les qualificacions d'àrea assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getAreaEvaluation($studentId, $areaId, $courseId=null, $trimestreId=null)
	{
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$student = $this->dao->getById("Student", $studentId);
		$area = $this->dao->getById("Area", $areaId);
		return $student->getAreaEvaluation($area, $course, $trimestre);
	}
	
	/**
	 * Retorna les qualificacions d'àmbit assignades a un alumne
	 * Si els períodes són nuls, retorna les qualificacions actuals
	 */
	public function getScopeEvaluation($studentId, $scopeId, $courseId=null, $trimestreId=null)
	{
		$course = $this->getCourse($courseId);
		$trimestre = $this->getTrimestre($trimestreId);
		$student = $this->dao->getStudent($studentId);
		$scope = $this->dao->getScope($scopeId);
		return $student->getScopeEvaluation($scope, $course, $trimestre);
	}
	
	/**
	 * Retorna les qualificacions finals parcials assignades a un alumne
	 * Si el curs és nul, retorna les qualificacions finals actuals
	 */
	public function getFinalDimensionEvaluation($studentId, $dimensionId, $courseId=null)
	{
		$course = $this->getCourse($courseId);
		$student = $this->dao->getById("Student", $studentId);
		$dimension = $this->dao->getById("Dimension", $dimensionId);
		return $student->getFinalDimensionEvaluation($dimension, $course);
	}
	
	/**
	 * Retorna les qualificacions finals d'àrea assignades a un alumne
	 * Si el curs és nul, retorna les qualificacions finals actuals
	 */
	public function getFinalAreaEvaluation($studentId, $areaId, $courseId=null)
	{
		$course = $this->getCourse($courseId);
		$student = $this->dao->getById("Student", $studentId);
		$area = $this->dao->getById("Area", $areaId);
		return $student->getFinalAreaEvaluation($area, $course);
	}
	
	/**
	 * Assigna una qualificació parcial a un alumne
	 */
	public function setPartialEvaluation($teacherId, $studentId, $dimensionId, $markId)
	{
		$course = $this->dao->getActiveCourse();
		$trimestre = $this->dao->getActiveTrimestre();
		$dimension = $this->dao->getById("Dimension", $dimensionId);
		$ed = $this->dao->getById("PartialEvaluationDescription", $markId);
		$student = $this->dao->getById("Student", $studentId);
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$evaluation = $student->getDimensionEvaluation($dimension, $course, $trimestre);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createDimensionEvaluation($teacher, $dimension, $course, $trimestre, $ed);
			$this->dao->persist($evaluation);
		} else { 
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->dao->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setPartialEvaluationDescription($ed);
				$evaluation->setTeacher($teacher);
				$this->dao->persist($evaluation);
			}
		}
		$this->dao->flush($evaluation);
	}
	
	public function setFinalPartialEvaluation($teacherId, $studentId, $dimensionId, $markId)
	{
		$course = $this->dao->getActiveCourse();
		$dimension = $this->dao->getById("Dimension", $dimensionId);
		$ed = $this->dao->getById("PartialEvaluationDescription", $markId);
		$student = $this->dao->getById("Student", $studentId);
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$evaluation = $student->getFinalDimensionEvaluation($dimension, $course);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createDimensionEvaluation($teacher, $dimension, $course, null, $ed);
			$this->dao->persist($evaluation);
		} else {
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->dao->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setPartialEvaluationDescription($ed);
				$evaluation->setTeacher($teacher);
				$this->dao->persist($evaluation);
			}
		}
		$this->dao->flush($evaluation);
	}

	/**
	 * Assigna una qualificació global a un alumne
	 */
	public function setGlobalEvaluation($teacherId, $studentId, $areaId, $markId)
	{
		$course = $this->dao->getActiveCourse();
		$trimestre = $this->dao->getActiveTrimestre();
		$area = $this->dao->getById("Area", $areaId);
		$ed = $this->dao->getById("GlobalEvaluationDescription", $markId);
		$student = $this->dao->getById("Student", $studentId);
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$evaluation = $student->getAreaEvaluation($area, $course, $trimestre);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createAreaEvaluation($teacher, $area, $course, $trimestre, $ed);
			$this->dao->persist($evaluation);
		} else {
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->dao->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setGlobalEvaluationDescription($ed);
				$evaluation->setTeacher($teacher);
				$this->dao->persist($evaluation);
			}
		}
		$this->dao->flush($evaluation);
	}
	
	public function setFinalGlobalEvaluation($teacherId, $studentId, $areaId, $markId)
	{
		$course = $this->dao->getActiveCourse();
		$area = $this->dao->getById("Area", $areaId);
		$ed = $this->dao->getById("GlobalEvaluationDescription", $markId);
		$student = $this->dao->getById("Student", $studentId);
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$evaluation = $student->getFinalAreaEvaluation($area, $course);
		if ($evaluation == null) {
			//if not exists, create new evaluation
			$evaluation = $student->createAreaEvaluation($teacher, $area, $course, null, $ed);
			$this->dao->persist($evaluation);
		} else {
			if ($markId == 0) {
				//if evaluation is null, delete evaluation
				$this->dao->remove($evaluation);
			} else {
				//if exists, update evaluation
				$evaluation->setGlobalEvaluationDescription($ed);
				$evaluation->setTeacher($teacher);
				$this->dao->persist($evaluation);
			}
		}
		$this->dao->flush($evaluation);
	}
	
	/**
	 * Assigna una observació general o de reforç a un alumne
	 */
	public function setObservation($teacherId, $studentId, $text, $reinforceId = null)
	{
		$course = $this->dao->getActiveCourse();
		$trimestre = $this->dao->getActiveTrimestre();
		$student = $this->dao->getById("Student", $studentId);
		$teacher = $this->dao->getById("Teacher", $teacherId);
		$reinforceClassroom = $reinforceId ? $this->dao->getById("ReinforceClassroom", $reinforceId) : null;
		$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
		if ($observation == null) {
			//create new observation
			$observation = $student->createObservation($text, $teacher, $course, $trimestre, $reinforceClassroom);
			$this->dao->persist($observation);
		} else {
			if (strlen(trim($text, '"')) == 0) {
				//delete observation
				$this->dao->remove($observation);
			} else {
				//update observation
				$observation->setText($text);
				$observation->setTeacher($teacher);
				$this->dao->persist($observation);
			}
		}
		$this->dao->flush($observation);
	}
}
<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\SchoolViewModel;
use infojor\presentation\model\UserViewModel;
use infojor\presentation\model\EvaluationViewModel;
use infojor\service\EvaluationService;
use infojor\presentation\view\TplEngine;

class EvaluateController extends Controller
{
	public function getClassroomStudents($classroomId)
	{
		$model = new SchoolViewModel();
		return $model->getCurrentClassroomStudents($classroomId);
	}

	public function getThumbnail() {
		$studentId = $_POST[STUDENT_ID];
		$model = new UserViewModel();
		return $model->getThumbnail($studentId);
	}
	
	public function getClassroomName($classroomId)
	{
		$model = new SchoolViewModel();
		return $model->getClassroom($classroomId);
	}
	
	public function getStudentName($studentId)
	{
		$model = new UserViewModel();
		return $model->getStudent($studentId);
	}
	
	/**
	 * Llegeix les variables de POST i de sessió i les passa a EvaluationViewModel
	 * El resultat l'envia a un generador d'HTML (tplEngine) i retorna el resultat
	 */
	public function getEvaluations()
	{
		if (session_status() != PHP_SESSION_ACTIVE) session_start();
		$studentId = $_POST[STUDENT_ID];
		$areaId = $_POST[AREA_ID];
		$reinforceId = $_POST[REINFORCE_ID];
		$section = $_SESSION[SECTION];
		if (strcmp($section, 'specialities') != 0) $areaId = null;
		if (strcmp($section, 'reinforcings') != 0) $reinforceId = null;
		$model = new EvaluationViewModel();
		$evaluation = $model->getEvaluations($studentId, $areaId, $reinforceId, true);
		$tplEngine = new TplEngine();
		$data = $tplEngine->output('createEvaluations', $evaluation);
		return json_encode($data, JSON_UNESCAPED_SLASHES);
	}
	
	public function setPartialEvaluation()
	{
		if (session_status() != PHP_SESSION_ACTIVE) session_start();
		$teacherId = $_SESSION[USER_ID];
		$studentId = $_POST[STUDENT_ID];
		$dimensionId = $_POST[DIMENSION_ID];
		$markId = $_POST[MARK_ID];
		$model = new EvaluationService();
		return $model->setPartialEvaluation($teacherId, $studentId, $dimensionId, $markId);
	}
	
	public function setGlobalEvaluation()
	{
		if (session_status() != PHP_SESSION_ACTIVE) session_start();
		$teacherId = $_SESSION[USER_ID];
		$studentId = $_POST[STUDENT_ID];
		$areaId = $_POST[AREA_ID];
		$markId = $_POST[MARK_ID];
		$model = new EvaluationService();
		return $model->setGlobalEvaluation($teacherId, $studentId, $areaId, $markId);
	}
	
	public function setObservation()
	{
		if (session_status() != PHP_SESSION_ACTIVE) session_start();
		$teacherId = $_SESSION[USER_ID];
		$studentId = $_POST[STUDENT_ID];
		$text = $_POST[OBSERVATION];
		$reinforceId = $_POST[REINFORCE_ID];
		$model = new EvaluationService();
		return $model->setObservation($teacherId, $studentId, $text, $reinforceId);
	}
}
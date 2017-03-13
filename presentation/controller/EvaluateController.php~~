<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\SchoolViewModel;
use tfg\presentation\model\UserViewModel;
use tfg\presentation\model\EvaluationViewModel;
use tfg\service\EvaluationService;
use tfg\presentation\view\TplEngine;

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
		$studentId = $_POST[STUDENT_ID];
		$dimensionId = $_POST[DIMENSION_ID];
		$markId = $_POST[MARK_ID];
		$model = new EvaluationService();
		return $model->setPartialEvaluation($studentId, $dimensionId, $markId);
	}
	
	public function setGlobalEvaluation()
	{
		$studentId = $_POST[STUDENT_ID];
		$areaId = $_POST[AREA_ID];
		$markId = $_POST[MARK_ID];
		$model = new EvaluationService();
		return $model->setGlobalEvaluation($studentId, $areaId, $markId);
	}
	
	public function setObservation()
	{
		$studentId = $_POST[STUDENT_ID];
		$text = $_POST[OBSERVATION];
		$reinforceId = $_POST[REINFORCE_ID];
		$model = new EvaluationService();
		return $model->setObservation($studentId, $text, $reinforceId);
	}
}
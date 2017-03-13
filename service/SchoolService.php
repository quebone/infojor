<?php
namespace infojor\service;

use infojor\service\Entities\Cycle;
use infojor\service\Entities\Course;
use infojor\utils\Utils;
use infojor\service\Entities\Classroom;
use infojor\service\Entities\Teacher;
use infojor\service\Entities\Trimestre;
use infojor\service\Entities\Area;
use infojor\service\Entities\ReinforceClassroom;
use infojor\service\Entities\Dimension;
use infojor\service\Entities\Degree;
use infojor\service\DAO;

final class SchoolService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getClassroomScopes($classroomId)
	{
		return $this->dao->getById("Classroom", $classroomId)->getScopes();
	}
	
	public function getDegreeScopes($degreeId)
	{
		return $this->dao->getById("Degree", $degreeId)->getScopes();
	}
	
	public function getScopeAreas($scopeId)
	{
		return $this->dao->getById("Scope", $scopeId)->getAreas();
	}
	
	public function getAreaDimensions($areaId, Cycle $cycle = null, $onlyActive = true)
	{
		return $this->dao->getById("Area", $areaId)->getDimensions($cycle, $onlyActive);
	}
	
	public function getPreviousTrimestres():array
	{
		$allTrimestres = $this->dao->getByFilter("Trimestre", [], ['number'=>'ASC']);
		$at = $this->dao->getActiveTrimestre();
		$trimestres = array();
		foreach ($allTrimestres as $trimestre) {
			if ($trimestre->getNumber() < $at->getNumber()) array_push($trimestres, $trimestre);
		}
		return $trimestres;
	}
	
	/**
	 * Retorna els alumnes actuals d'una classe
	 */
	public function getCurrentClassroomStudents($classroomId) {
		return $this->dao->getSchool()->getClassroomStudents(
			$this->dao->getById("Classroom", $classroomId),
			$this->dao->getActiveCourse(),
			$this->dao->getActiveTrimestre()
		);
	}
	
	public function getDegreeAreas($degreeId):array {
		$areas = array();
		$degree = $this->dao->getById("Degree", $degreeId);
		$scopes = $degree->getScopes();
		foreach ($scopes as $scope) {
			foreach ($scope->getAreas() as $area) {
				array_push($areas, $area);
			}
		}
		return $areas;
	}
	
	/**
	 * Crea un nou curs
	 */
	public function createCourse($year) {
		$course = new Course($year);
		$this->dao->persist($course);
		try {
			$this->dao->flush();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
		return true;
	}
	
	/**
	 * Actualitza les dades d'un curs i l'activa o el desactiva
	 */
	public function updateCourse($courseId, $year, $isActive) {
		$course = $this->dao->getById("Course", $courseId);
		$school = $this->dao->getSchool();
		$course->setYear($year);
		if ($isActive) $school->setActiveCourse($course);
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Elimina un curs després de comprovar que no té cap qualificació assignada
	 */
	public function deleteCourse($courseId) {
		$course = $this->dao->getById("Course", $courseId);
		// entities associated to a course
		$entities = array("Tutoring", "Speciality", "Reinforcing", "PartialEvaluation", "GlobalEvaluation", "Enrollment");
		foreach ($entities as $entity) {
			if ($this->dao->getByFilter($entity, ['course' => $course]) != null)
				return false;
		}
		$this->dao->remove($course);
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Estableix un trimestre com a actiu
	 */
	public function setActiveTrimestre($trimestreId)
	{
		$trimestre = $this->dao->getById("Trimestre", $trimestreId);
		$school = $this->dao->getSchool();
		$school->setActiveTrimestre($trimestre);
		$this->dao->flush();
	}
	
	/**
	 * Envia un correu electrònic a tots els administradors
	 */
	public function sendMessage($userId, $message)
	{
		$userModel = new UserService();
		$user = $this->dao->getById("Teacher", $userId);
		$teachers = $this->dao->getByFilter("Teacher");
		
		require_once BASEDIR.'/vendor/PHPMailer/PHPMailerAutoload.php';
		$mail = new \PHPMailer();
		$mail->IsSMTP();
		$mail->Host = "mail.jor.cat";
		$mail->SMTPAuth = true;
		$mail->Username = "info@jor.cat";
		$mail->Password = "escola0519";
		
		$mail->From = $user->getEmail();
		$mail->FromName = Utils::decode($user->getName() . " " . $user->getSurnames());
		
		foreach ($teachers as $teacher) {
			if ($teacher->isAdmin()) {
// 				$mail->AddAddress($teacher->getEmail(), $teacher->getName() . " " . $teacher->getSurnames());
			}
		}
		$mail->AddAddress("carles@simi.cat", "Carles Canellas");
		$mail->AddReplyTo($user->getEmail(), Utils::decode($user->getName() . " " . $user->getSurnames()));
		
		$mail->WordWrap = 50;
		$mail->IsHTML(false);
		
		$mail->Subject = "Informes trimestrals";
		$mail->Body = stripcslashes(Utils::decode(trim($message, '"')));
		
		if(!$mail->Send()) return $mail->ErrorInfo;
		return true;
	}
	
	/**
	 * Retorna totes les tutories actuals
	 */
	public function getCurrentTutorings():array
	{
		$data = array();
		$classrooms = $this->dao->getByFilter("Classroom");
		foreach ($classrooms as $classroom) {
			array_push($data, array('classroom' => $classroom,
					'tutors' => $classroom->getTutors($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre())->toArray()
				));
		}
		return $data;
	}
	
	/**
	 * Retorna totes les especialitats actuals
	 */
	public function getCurrentSpecialities()
	{
		$data = array();
		$areas = $this->dao->getByFilter("Area");
		foreach ($areas as $area) {
			array_push($data, array('area' => $area,
				'specialists' => $area->getSpecialists($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre())->toArray()
			));
		}
		return $data;
	}

	/**
	 * Retorna totes les aules de reforç actuals
	 */
	public function getCurrentReinforcings()
	{
		$data = array();
		$reinforcings = $this->dao->getByFilter("ReinforceClassroom");
		foreach ($reinforcings as $reinforcing) {
			array_push($data, array('classroom' => $reinforcing,
					'reinforcers' => $reinforcing->getReinforcers($this->dao->getActiveCourse(), $this->dao->getActiveTrimestre())->toArray()
			));
		}
		return $data;
	}
	
	/**
	 * Elimina una dimensió
	 */
	public function deleteDimension($dimId)
	{
		$dimension = $this->dao->getById("Dimension", $dimId);
		if ($dimension->getPartialEvaluations()->count() == 0) {
			$this->dao->remove($dimension);
			$this->dao->flush();
			return true;
		}
		return false;
	}
	
	/**
	 * Actualitza una dimensió
	 */
	public function updateDimension($dimId, $name, $description, $cycleIds, $active)
	{
		$dimension = $this->dao->getById("Dimension", $dimId);
		$dimension->setName($name);
		$dimension->setDescription($description);
		foreach ($cycleIds as $cycleId)
			if (!$dimension->getCycles()->contains($this->dao->getById("Cycle", $cycleId)))
				$dimension->addCycle($this->dao->getById("Cycle", $cycleId));
		foreach ($dimension->getCycles() as $cycle)
			if (!in_array($cycle->getId(), $cycleIds))
				$dimension->removeCycle($cycle);
		$dimension->setActive($active);
		$this->dao->flush();
		return true;
	}
	
	/**
	 * Crea una dimensió
	 */
	public function addDimension($name, $description, $areaId, $cycleIds)
	{
		$dimension = new Dimension($name, $description);
		$area = $this->dao->getById("Area", $areaId);
		$dimension->setArea($area);
		foreach ($cycleIds as $cycleId) $dimension->addCycle($this->dao->getById("Cycle", $cycleId));
		$this->dao->persist($dimension);
		$this->dao->flush();
		return true;
	}
}
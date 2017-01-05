<?php
namespace tfg\service;

use tfg\service\Entities\Cycle;
use tfg\service\Entities\Course;
use tfg\utils\Utils;

final class SchoolService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getClassroom($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Classroom', $id);
	}
	
	public function getClassrooms()
	{
		return $this->entityManager->getRepository('tfg\\service\\Entities\\Classroom')->findAll();
	}
	
	public function getCurrentClassroomStudents($classroomId) {
		return $this->getSchool()->getClassroomStudents(
			$this->getClassroom($classroomId),
			$this->getActiveCourse(),
			$this->getActiveTrimestre()
		);
	}
	
	public function getScope($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Scope', $id);
	}
	
	public function getArea($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Area', $id);
	}
	
	public function getReinforceClassroom($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\ReinforceClassroom', $id);
	}
	
	public function getDimension($id)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Dimension', $id);
	}
	
	public function getClassroomScopes($classroomId)
	{
		return $this->getClassroom($classroomId)->getScopes();
	}
	
	public function getScopeAreas($scopeId)
	{
		return $this->getScope($scopeId)->getAreas();
	}

	public function getAreaDimensions($areaId, Cycle $cycle = null)
	{
		return $this->getArea($areaId)->getDimensions($cycle);
	}
	
	public function getCourses()
	{
		return $this->entityManager->getRepository('tfg\\service\\Entities\\Course')->findBy([], ['year' => 'ASC']);
	}
	
	public function getTrimestres()
	{
		return $this->entityManager->getRepository('tfg\\service\\Entities\\Trimestre')->findBy([], ['number' => 'ASC']);
	}
	
	public function createCourse($year) {
		$course = new Course($year);
		$this->entityManager->persist($course);
		try {
			$this->entityManager->flush();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
		return true;
	}
	
	public function updateCourse($courseId, $year, $isActive) {
		$course = $this->getCourse($courseId);
		$school = $this->getSchool();
		$course->setYear($year);
		if ($isActive) $school->setActiveCourse($course);
		$this->entityManager->flush();
		return true;
	}
	
	public function deleteCourse($courseId) {
		$course = $this->getCourse($courseId);
		// entities associated to a course
		$entities = array("Tutoring", "Speciality", "Reinforcing", "PartialEvaluation", "GlobalEvaluation", "Enrollment");
		foreach ($entities as $entity) {
			if ($this->entityManager->getRepository('tfg\\service\\Entities\\' . $entity)->findBy(['course' => $course]) != null)
				return false;
		}
		$this->entityManager->remove($course);
		$this->entityManager->flush();
		return true;
	}
	
	public function setActiveTrimestre($trimestreId)
	{
		$trimestre = $this->getTrimestre($trimestreId);
		$school = $this->getSchool();
		$school->setActiveTrimestre($trimestre);
		$this->entityManager->flush();
	}
	
	public function sendMessage($userId, $message)
	{
		$userModel = new UserService();
		$user = $userModel->getTeacher($userId);
		$teachers = $userModel->getAllTeachers();
		
		require_once BASEDIR.'PHPMailer/PHPMailerAutoload.php';
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
	
	public function getCurrentTutorings()
	{
		//TODO
	}

	public function getCurrentSpecialities()
	{
		//TODO
	}

	public function getCurrentReinforcings()
	{
		//TODO
	}
}
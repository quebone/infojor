<?php
namespace Infojor\Service;

use Infojor\Service\Entities\Teacher;
use Infojor\Service\Entities\Student;
use Infojor\Service\Entities\Log;

final class UserService extends MainService
{
	public function __construct($entityManager) {
		parent::__construct($entityManager);
	}
	
	public function login($username, $password)
	{
		$teacher = $this->entityManager->getRepository('Infojor\\Service\\Entities\\Teacher')->findOneBy(array(
			'username'=>$username,
			'password'=>sha1($password),
			));
		//creates a new log
		$date = new \DateTime();
		$logged = $teacher != null;
		$log = new Log($username, $date, $logged);
		$this->entityManager->persist($log);
		$this->entityManager->flush($log);
		return $teacher;
	}
	
	public function getPerson($id)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Person', $id);
	}
	
	public function getTeacher($id):Teacher
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Teacher', $id);
	}
	
	public function getStudent($id):Student
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Student', $id);
	}
	
	public function getCurrentTutorings($teacherid):array
	{
		$teacher = $this->getTeacher($teacherid);
		return $teacher->getTutorings($this->entityManager, $this->getActiveCourse(), $this->getActiveTrimestre());
	}

	public function getCurrentSpecialities($teacherid):array
	{
		$teacher = $this->getTeacher($teacherid);
		return $teacher->getSpecialities($this->entityManager, $this->getActiveCourse(), $this->getActiveTrimestre());
	}

	public function getCurrentReinforcings($teacherid):array
	{
		$teacher = $this->getTeacher($teacherid);
		return $teacher->getReinforcings($this->entityManager, $this->getActiveCourse(), $this->getActiveTrimestre());
	}
}
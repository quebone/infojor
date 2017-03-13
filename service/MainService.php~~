<?php
namespace tfg\service;

use tfg\service\Entities\School;

abstract class MainService
{
	protected $entityManager;
	
	public function __construct() {
		$this->entityManager = \tfg\utils\Utils::getEm();
	}
	
	public function getSchool():School
	{
		$school = $this->entityManager->find('tfg\\service\\Entities\\School', 1);
		return $school;
	}

	public function getActiveCourse()
	{
		return $this->getSchool()->getActiveCourse();
	}

	public function getActiveTrimestre()
	{
		return $this->getSchool()->getActiveTrimestre();
	}
	
	public function getCourse($courseId)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Course', $courseId);
	}
	
	public function getTrimestre($trimestreId)
	{
		return $this->entityManager->find('tfg\\service\\Entities\\Trimestre', $trimestreId);
	}
}
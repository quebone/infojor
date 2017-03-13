<?php
namespace Infojor\Service;

abstract class MainService
{
	protected $entityManager;
	
	public function __construct(\Doctrine\ORM\EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}
	
	public function getSchool()
	{
		$school = $this->entityManager->find('Infojor\\Service\\Entities\\School', 1);
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
		return $this->entityManager->find('Infojor\\Service\\Entities\\Course', $courseId);
	}
	
	public function getTrimestre($trimestreId)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Trimestre', $trimestreId);
	}
}
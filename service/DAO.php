<?php
namespace infojor\service;

use infojor\utils\Utils;
use infojor\service\Entities\School;

class DAO
{
	private $entityManager;
	
	public function __construct()
	{
		$this->entityManager = Utils::getEm();
	}
	
	public function getById($entity, $id)
	{
		return $this->entityManager->find('infojor\\service\\Entities\\' . $entity, $id);
	}
	
	public function getByFilter($entity, $filter = array(), $order = array())
	{
		return $this->entityManager->getRepository('infojor\\service\\Entities\\' . $entity)->findBy($filter, $order);
	}

	public function persist($object)
	{
		$this->entityManager->persist($object);
	}
	
	public function remove($object)
	{
		$this->entityManager->remove($object);
	}
	
	public function flush()
	{
		$this->entityManager->flush();
	}
	
	public function getActiveCourse()
	{
		return $this->getSchool()->getActiveCourse();
	}
	
	public function getActiveTrimestre()
	{
		return $this->getSchool()->getActiveTrimestre();
	}
	
	public function getSchool():School
	{
		return $this->entityManager->find('infojor\\service\\Entities\\School', 1);
	}
}
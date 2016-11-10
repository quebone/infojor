<?php
namespace Infojor\Service;

use Infojor\Service\Entities\Cycle;

final class SchoolService extends MainService
{
	public function __construct($entityManager) {
		parent::__construct($entityManager);
	}
	
	public function getPerson($id)
	{
		$person = $this->entityManager->find('Infojor\\Service\\Entities\\Person', $id);
		return $person;
	}
	
	public function getClassroom($id)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Classroom', $id);
	}
	
	public function getCurrentClassroomStudents($classroomId) {
		return $this->getSchool()->getClassroomStudents(
			$this->entityManager,
			$this->getClassroom($classroomId),
			$this->getActiveCourse(),
			$this->getActiveTrimestre()
		);
	}
	
	public function getScope($id)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Scope', $id);
	}
	
	public function getArea($id)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Area', $id);
	}
	
	public function getDimension($id)
	{
		return $this->entityManager->find('Infojor\\Service\\Entities\\Dimension', $id);
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
}
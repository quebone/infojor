<?php
namespace infojor\service;

abstract class MainService
{
	protected $entityManager;
	protected $dao;
	
	public function __construct() {
		$this->entityManager = \infojor\utils\Utils::getEm();
		$this->dao = new DAO();
	}
	
	protected function getCourse($courseId = null)
	{
		if ($courseId == null) {
			return $this->dao->getActiveCourse();
		} else {
			return $this->dao->getById("Course", $courseId);
		}
	}

	protected function getTrimestre($trimestreId = null)
	{
		if ($trimestreId == null) {
			return $this->dao->getActiveTrimestre();
		} else {
			return $this->dao->getById("Trimestre", $trimestreId);
		}
	}
}
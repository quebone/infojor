<?php
namespace Infojor\Presentation\Model;

class ViewModel
{
	protected $model;
	protected $data;
	protected $entityManager;

	public function __construct($model = null, \Doctrine\ORM\EntityManager $entityManager = null)
	{
		$this->model = $model;
		$this->entityManager = $entityManager;
		$this->data = new \stdClass;
	}
	
	public function getModel()
	{
		return $this->model;
	}
	
	public function setModel($model)
	{
		$this->model = $model;
	}
}
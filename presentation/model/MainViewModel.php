<?php
namespace tfg\presentation\model;

class MainViewModel implements IViewModel
{
	protected $model;
	protected $data;
	protected $entityManager;

	public function __construct($model = null)
	{
		$this->model = $model;
		$this->entityManager = \tfg\utils\Utils::getEm();
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
	
	protected function addData($newData)
	{
		$this->data = (object) array_merge((array) $newData, (array) $this->data);
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function setSession()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		foreach ($_POST as $key=>$value) {
			$_SESSION[$key] = $value;
		}
	}

	protected function getSessionVar($sessionVar)
	{
		if (isset($_SESSION[$sessionVar])) return $_SESSION[$sessionVar];
		return false;
	}
}
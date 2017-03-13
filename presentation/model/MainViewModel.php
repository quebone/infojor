<?php
namespace infojor\presentation\model;

use infojor\service\DAO;

class MainViewModel
{
	protected $model;
	protected $data;
	protected $entityManager;
	protected $dao;

	public function __construct($model = null)
	{
		$this->model = $model;
		$this->dao = new DAO();
		$this->entityManager = \infojor\utils\Utils::getEm();
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
	
	/**
	 * Converteix les variables obtingudes per POST a variables de sessió 
	 */
	public function setSession()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		foreach ($_POST as $key=>$value) {
			$_SESSION[$key] = $value;
		}
	}

	/**
	 * Retorna el valor d'una variable de sessió
	 */
	protected function getSessionVar($sessionVar)
	{
		if (session_status() != PHP_SESSION_ACTIVE) session_start();
		if (isset($_SESSION[$sessionVar])) return $_SESSION[$sessionVar];
		return false;
	}
}
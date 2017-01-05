<?php
namespace tfg\presentation\controller;

class Controller {
	function __construct() {
	}
	
	public function setSession()
	{
		$model = new \tfg\presentation\model\MainViewModel();
		$model->setSession();
		return json_encode($_POST);
	}
	
	public function printReport() {
		$this->setSession();
	}
	
	public function isAdmin() {
		$model = new \tfg\service\UserService();
		return $model->isAdmin($_SESSION[USER_ID]);
	}
}
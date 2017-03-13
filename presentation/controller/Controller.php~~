<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\MainViewModel;
use tfg\service\UserService;

class Controller {
	function __construct() {
	}
	
	public function setSession()
	{
		$model = new MainViewModel();
		$model->setSession();
		return json_encode($_POST);
	}
	
	public function printReport() {
		$this->setSession();
	}
	
	public function isAdmin() {
		$model = new UserService();
		return $model->isAdmin($_SESSION[USER_ID]);
	}
}
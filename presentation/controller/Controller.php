<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\MainViewModel;
use infojor\service\UserService;
use infojor\service\DAO;

class Controller {
	protected $dao;
	
	public function __construct() {
		$this->dao = new DAO();
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
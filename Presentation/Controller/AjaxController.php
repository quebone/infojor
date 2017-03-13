<?php
namespace Infojor\Presentation\Controller;

define('AJAX', true);

if (!defined('REQUIRED')) {
	require_once '../../presentation/model/frontcontroller/AjaxFrontController.php';
}

if (isset($_POST['function'])) {
	$ac = new AjaxController();
	$ac->loadFunction($_POST['function']);
}

class AjaxController
{
	private $frontController;

	function __construct() {
		$this->frontController = new \Infojor\Presentation\Model\FrontController\AjaxFrontController();
	}

	// loads a function from a class
	public function loadFunction($fName)
	{
		echo $this->frontController->{$fName}();
	}
}

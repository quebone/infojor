<?php
namespace tfg\presentation\controller;

define("BASEDIR", "../../");
require BASEDIR."init.php";

if (isset($_POST['function']) && isset($_POST['caller'])) {
	$controller = $_POST['caller'] . "Controller";
	$ac = new AjaxController($controller);
	$ac->loadFunction($_POST['function']);
}

class AjaxController
{
	private $controller;

	function __construct($controller) {
		$controller = __NAMESPACE__ . "\\" . $controller;
		$this->controller = new $controller();
	}

	// loads a function from a class
	public function loadFunction($fName)
	{
		echo $this->controller->{$fName}();
	}
}

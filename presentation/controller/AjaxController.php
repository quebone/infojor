<?php
namespace tfg\presentation\controller;

/**
 * Canvia la definició del directori base, ja que la classe està en un subdirectori
 */
define("BASEDIR", "../../");
require BASEDIR."init.php";

/**
 * Crida el la funció del controalador segons les variables POST obtingudes
 */
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

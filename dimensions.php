<?php
namespace tfg;

use tfg\presentation\controller\DimensionsController;

session_start();

require_once 'init.php';

if (isset($_SESSION[USER_ID])) {
	$userId = $_SESSION[USER_ID];
} else {
	header('Location: login.php');
}

?>
<!doctype html>
<?php

$controller = new DimensionsController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new \tfg\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$data['degrees'] = $controller->getDegrees();

$template = new \Transphporm\Builder(TPLDIR.'dimensions.xml', TPLDIR.'dimensions.tss');

echo $template->output($data)->body;

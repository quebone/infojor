<?php
namespace tfg;

use tfg\presentation\controller\ReinforcingsController;
use tfg\presentation\model\HeaderViewModel;

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

$controller = new ReinforcingsController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new HeaderViewModel();
$data['header'] = $header->output();

$data['reinforcings'] = $controller->listAllReinforcings();
$data['classrooms'] = $controller->getClassrooms();
$data['teachers'] = $controller->getTeachers();

$template = new \Transphporm\Builder(TPLDIR.'reinforcings.xml', TPLDIR.'reinforcings.tss');

echo $template->output($data)->body;

<?php
namespace tfg;

use tfg\presentation\controller\TutoringsController;

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

$controller = new TutoringsController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new \tfg\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$data['tutorings'] = $controller->listAllTutorings();
$data['classrooms'] = $controller->getClassrooms();
$data['teachers'] = $controller->getTeachers();

$template = new \Transphporm\Builder(TPLDIR.'tutorings.xml', TPLDIR.'tutorings.tss');

echo $template->output($data)->body;

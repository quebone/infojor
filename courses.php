<?php
namespace tfg;

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

$controller = new \infojor\presentation\controller\CoursesController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new \infojor\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$data['courses'] = $controller->getCourses();
$data['activeTrimestre'] = $controller->getActiveTrimestre();

$template = new \Transphporm\Builder(TPLDIR.'courses.xml', TPLDIR.'courses.tss');

echo $template->output($data)->body;

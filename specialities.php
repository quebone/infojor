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

$controller = new \tfg\presentation\controller\SpecialitiesController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new \tfg\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

// $data['teacher'] = $controller->getUserData();

$template = new \Transphporm\Builder(TPLDIR.'specialities.xml', TPLDIR.'specialities.tss');

echo $template->output($data)->body;

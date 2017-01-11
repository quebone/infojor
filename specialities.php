<?php
namespace tfg;

use tfg\presentation\controller\SpecialitiesController;
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

$controller = new SpecialitiesController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new HeaderViewModel();
$data['header'] = $header->output();

$data['specialities'] = $controller->listAllSpecialities();
$data['areas'] = $controller->getAreas();
$data['teachers'] = $controller->getTeachers();

$template = new \Transphporm\Builder(TPLDIR.'specialities.xml', TPLDIR.'specialities.tss');

echo $template->output($data)->body;

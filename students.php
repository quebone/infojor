<?php
namespace tfg;

use infojor\presentation\model\HeaderViewModel;
use infojor\presentation\view\HeaderEngine;
use infojor\presentation\controller\StudentsController;

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

$controller = new StudentsController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new HeaderViewModel([1, 2, 3]);
$data['header'] = $header->output();
$engine = new HeaderEngine();
$headerFile = file_get_contents(TPLDIR . "header.xml");
$headerFile = str_replace("#menus#", $engine->header($data['header']), $headerFile);
$footerFile = file_get_contents(TPLDIR . "footer.xml");
$footerFile = str_replace("#footer#", $engine->footer($data['header']), $footerFile);

$data['classrooms'] = $controller->getClassrooms();

$template = new \Transphporm\Builder(TPLDIR.'students.xml', TPLDIR.'students.tss');
$template = $template->output($data)->body;

$template = str_replace("#header#", $headerFile, $template);
$template = str_replace("#footer#", $footerFile, $template);

echo $template;

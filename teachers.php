<?php
namespace infojor;

use infojor\presentation\controller\TeachersController;
use infojor\presentation\model\HeaderViewModel;
use infojor\presentation\view\HeaderEngine;

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

$controller = new TeachersController();
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

$data['teachers'] = $controller->getTeachers();

$template = new \Transphporm\Builder(TPLDIR.'teachers.xml', TPLDIR.'teachers.tss');
$template = $template->output($data)->body;

$template = str_replace("#header#", $headerFile, $template);
$template = str_replace("#footer#", $footerFile, $template);

echo $template;

<?php
namespace tfg;

use infojor\presentation\view\HeaderEngine;
use infojor\presentation\model\HeaderViewModel;

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

$header = new HeaderViewModel([1, 2, 3]);
$data['header'] = $header->output();
$engine = new HeaderEngine();
$headerFile = file_get_contents(TPLDIR . "header.xml");
$headerFile = str_replace("#menus#", $engine->header($data['header']), $headerFile);
$footerFile = file_get_contents(TPLDIR . "footer.xml");
$footerFile = str_replace("#footer#", $engine->footer($data['header']), $footerFile);

$controller = new \infojor\presentation\controller\UserdataController();
$data['teacher'] = $controller->getUserData();

$template = new \Transphporm\Builder(TPLDIR.'userdata.xml', TPLDIR.'userdata.tss');
$template = $template->output($data)->body;

$template = str_replace("#header#", $headerFile, $template);
$template = str_replace("#footer#", $footerFile, $template);

echo $template;

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

$header = new \infojor\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$controller = new \infojor\presentation\controller\SendmessageController();
// $data['teacher'] = $controller->getUserData();

$template = new \Transphporm\Builder(TPLDIR.'sendmessage.xml', TPLDIR.'sendmessage.tss');

echo $template->output($data)->body;

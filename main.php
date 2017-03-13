<?php
namespace tfg;

use infojor\presentation\model\HeaderViewModel;
use infojor\presentation\controller\MainController;
use infojor\presentation\view\TplEngine;

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

$header = new HeaderViewModel();
$data['header'] = $header->output();

$controller = new MainController();
$tplEngine = new TplEngine();
$data['sections'] = $tplEngine->output('createSections', $controller->getSectionData($userId));
$template = new \Transphporm\Builder(TPLDIR.'main.xml', TPLDIR.'main.tss');
echo $template->output($data)->body;

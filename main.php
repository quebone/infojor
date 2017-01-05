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

$header = new \tfg\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$controller = new \tfg\presentation\controller\MainController();
$tplEngine = new \Simi\TplEngine\TplEngine();
$data['sections'] = $tplEngine->output('createSections', $controller->getSectionData($userId));
$template = new \Transphporm\Builder(TPLDIR.'main.xml', TPLDIR.'main.tss');
echo $template->output($data)->body;

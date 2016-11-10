<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\MainFrontController;

session_start();

?>
<!doctype html>
<?php

require_once 'init.php';

if (isset($_SESSION['userid'])) {
	$userId = $_SESSION['userid'];
} else {
 	header('Location: login.php');
	$userId = 1;
}

$frontController = new MainFrontController($userId, $entityManager);
$data = $frontController->getData();
$tplEngine = new \Simi\TplEngine\TplEngine();
$data->sections = $tplEngine->output('createSections', $frontController->getSectionData());
$template = new \Transphporm\Builder(TPLDIR.'main.xml', TPLDIR.'main.tss');
echo $template->output($data)->body;

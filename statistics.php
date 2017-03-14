<?php
namespace tfg;

use infojor\presentation\controller\StatisticsController;
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

$controller = new StatisticsController();
if (!$controller->isAdmin()) {
	echo "Pàgina visible només pels administradors";
	exit();
}

$header = new HeaderViewModel();
$data['header'] = $header->output();

$template = new \Transphporm\Builder(TPLDIR.'statistics.xml', TPLDIR.'statistics.tss');

echo $template->output($data)->body;

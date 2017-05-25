<?php
namespace tfg;

use infojor\presentation\model\HeaderViewModel;
use infojor\presentation\view\HeaderEngine;
use infojor\presentation\view\MainEngine;
use infojor\presentation\model\UserViewModel;

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

$model = new UserViewModel();
$data = $model->getCurrentSections($userId);

$mainFile = file_get_contents(TPLDIR . "main.xml");
$engine = new MainEngine();
$mainFile = str_replace("#tutorings#", $engine->outputSection($data, 'tutorings'), $mainFile);
$mainFile = str_replace("#specialities#", $engine->outputSection($data, 'specialities'), $mainFile);
$mainFile = str_replace("#reinforcings#", $engine->outputSection($data, 'reinforcings'), $mainFile);

$mainFile = str_replace("#header#", $headerFile, $mainFile);
$mainFile = str_replace("#footer#", $footerFile, $mainFile);

echo $mainFile;

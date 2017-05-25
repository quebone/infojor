<?php
namespace tfg;

use infojor\presentation\model\HeaderViewModel;
use infojor\presentation\controller\EvaluateController;
use infojor\presentation\view\HeaderEngine;

session_start();

require_once 'init.php';

/*
$_SESSION[USER_ID] = 1;
$_SESSION[CLASSROOM_ID] = 15;
$_SESSION[STUDENT_ID] = 54;
$_SESSION[AREA_ID] = null;
$_SESSION[SECTION] = 'specialities';
$_SESSION[REINFORCE_ID] = null;
*/

if (isset($_SESSION[USER_ID])) {
	$userId = $_SESSION[USER_ID];
	$classroomId = $_SESSION[CLASSROOM_ID];
	$studentId = $_SESSION[STUDENT_ID];
	$areaId = $_SESSION[AREA_ID];
	$section = $_SESSION[SECTION];
	$reinforceId = $_SESSION[REINFORCE_ID];
} else {
	header('Location: login.php');
}

if ($classroomId == null) $classroomId = DEFAULTCLASSROOMID;

?>
<!doctype html>
<?php

$header = new HeaderViewModel([1, 2, 3, 4]);
$data['header'] = $header->output();
$engine = new HeaderEngine();
$headerFile = file_get_contents(TPLDIR . "header.xml");
$headerFile = str_replace("#menus#", $engine->header($data['header']), $headerFile);
$footerFile = file_get_contents(TPLDIR . "footer.xml");
$footerFile = str_replace("#footer#", $engine->footer($data['header']), $footerFile);

$controller = new EvaluateController();
$data['students'] = $controller->getClassroomStudents($classroomId);
$data['classroom'] = $controller->getClassroomName($classroomId);
$data['areaId'] = $areaId;
$data['reinforceId'] = $reinforceId;
$data['section'] = $section;
$data['trimestres'] = $controller->getTrimestres();
$data['classrooms'] = $controller->getClassrooms($section, $classroomId);

$template = new \Transphporm\Builder(TPLDIR.'evaluate.xml', TPLDIR.'evaluate.tss');
$template = $template->output($data)->body;

$template = str_replace("#header#", $headerFile, $template);
$template = str_replace("#footer#", $footerFile, $template);

echo $template;

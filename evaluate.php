<?php
namespace tfg;

session_start();

require_once 'init.php';

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

$header = new \tfg\presentation\model\HeaderViewModel();
$data['header'] = $header->output();

$controller = new \tfg\presentation\controller\EvaluateController();
$data['students'] = $controller->getClassroomStudents($classroomId);
$data['classroom'] = $controller->getClassroomName($classroomId);
$data['areaId'] = $areaId;
$data['reinforceId'] = $reinforceId;
$data['section'] = $section;

$template = new \Transphporm\Builder(TPLDIR.'evaluate.xml', TPLDIR.'evaluate.tss');
echo $template->output($data)->body;

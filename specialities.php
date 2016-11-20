<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\EvaluateFrontController;

session_start();

require_once 'init.php';

if (isset($_SESSION['userid'])) {
	$userId = $_SESSION['userid'];
	$areaId = $_SESSION['areaId'];
	$classroomId = $_SESSION['classroomId'];
	$studentId = $_SESSION['studentid'];
} else {
 	header('Location: login.php');
// 	$_SESSION['userid'] = 1; $_SESSION['areaId'] = 12; $_SESSION['studentid'] = 15; $_SESSION['classroomId'] = 7;
// 	$userId = $_SESSION['userid'];
// 	$areaId = $_SESSION['areaId'];
// 	$studentId = $_SESSION['studentid'];
// 	$classroomId = $_SESSION['classroomId'];
}

?>
<!doctype html>
<?php

if ($classroomId == null) $classroomId = 7;

$frontController = new EvaluateFrontController($userId, $studentId, $classroomId, $areaId, null, $entityManager);
$data = $frontController->getData();
$data->classroomId = $classroomId;
$data->areaId = $areaId;
$template = new \Transphporm\Builder(TPLDIR.'tutorings.xml', TPLDIR.'tutorings.tss');
echo $template->output($data)->body;

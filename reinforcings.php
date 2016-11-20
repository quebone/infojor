<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\EvaluateFrontController;

session_start();

require_once 'init.php';

if (isset($_SESSION['userid'])) {
	$userId = (int) $_SESSION['userid'];
	$reinforceId = (int) $_SESSION['reinforceId'];
	$classroomId = (int) $_SESSION['classroomId'];
	$studentId = (int) $_SESSION['studentid'];
} else {
 	header('Location: login.php');
// 	$_SESSION['userid'] = 1; $_SESSION['reinforceId'] = 1; $_SESSION['studentid'] = 15; $_SESSION['classroomId'] = 7;
// 	$userId = $_SESSION['userid'];
// 	$reinforceId = $_SESSION['reinforceId'];
// 	$studentId = $_SESSION['studentid'];
// 	$classroomId = $_SESSION['classroomId'];
}

?>
<!doctype html>
<?php

if ($classroomId == null) $classroomId = 7;

$frontController = new EvaluateFrontController($userId, $studentId, $classroomId, null, $reinforceId, $entityManager);
$data = $frontController->getData();
$data->classroomId = $classroomId;
$data->reinforceId = $reinforceId;
$template = new \Transphporm\Builder(TPLDIR.'tutorings.xml', TPLDIR.'tutorings.tss');
echo $template->output($data)->body;

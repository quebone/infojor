<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\EvaluateFrontController;

session_start();

require_once 'init.php';

if (isset($_SESSION['userid']) && isset($_SESSION['classroomId'])) {
	$userId = $_SESSION['userid'];
	$classroomId = $_SESSION['classroomId'];
	$studentId = $_SESSION['studentid'];
} else {
 	header('Location: login.php');
// 	$_SESSION['userid'] = 1; $_SESSION['classroomId'] = 7; $_SESSION['studentid'] = 15;
// 	$userId = $_SESSION['userid'];
// 	$classroomId = $_SESSION['classroomId'];
// 	$studentId = $_SESSION['studentid'];
}

?>
<!doctype html>
<?php

$frontController = new EvaluateFrontController($userId, $studentId, $classroomId, null, null, $entityManager);
$data = $frontController->getData();
$data->classroomId = $classroomId;
$template = new \Transphporm\Builder(TPLDIR.'tutorings.xml', TPLDIR.'tutorings.tss');
echo $template->output($data)->body;

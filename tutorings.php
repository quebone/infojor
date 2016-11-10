<?php
namespace Infojor;

use Infojor\Presentation\Model\FrontController\EvaluateFrontController;

session_start();

?>
<!doctype html>
<?php

require_once 'init.php';

if (isset($_SESSION['userid']) && isset($_SESSION['sectionId'])) {
	$userId = $_SESSION['userid'];
	$classroomId = $_SESSION['sectionId'];
	$studentId = $_SESSION['studentid'];
} else {
//  	header('Location: login.php');
	$_SESSION['userid'] = 1; $_SESSION['sectionId'] = 7; $_SESSION['studentid'] = 15;
	$userId = $_SESSION['userid'];
	$classroomId = $_SESSION['sectionId'];
	$studentId = $_SESSION['studentid'];
}

$frontController = new EvaluateFrontController($userId, $studentId, $classroomId, null, $entityManager);
$data = $frontController->getData();
$data->classroomId = $classroomId;
$template = new \Transphporm\Builder(TPLDIR.'tutorings.xml', TPLDIR.'tutorings.tss');
echo $template->output($data)->body;

<?php
namespace tfg;

use infojor\presentation\view\PDFEngine;

session_start();

error_reporting(E_ALL); ini_set('display_errors', 'On');
require_once 'init.php';
require_once 'vendor/fpdf/fpdf.php';
require_once 'presentation/view/PDFEngine.php';

if (isset($_SESSION[USER_ID])) {
	$userId = $_SESSION[USER_ID];
} else {
 	header('Location: login.php');
}

$studentId = $_SESSION[STUDENT_ID];
$classroomId = $_SESSION[CLASSROOM_ID];

$config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);
$model = new \infojor\presentation\model\ReportViewModel($studentId, $classroomId);
$data = $model->getData();
$tplEngine = new PDFEngine($data);
$tplEngine->Output('I');

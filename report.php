<?php
namespace tfg;

use infojor\presentation\view\PDFEngine;
use infojor\presentation\model\ReportViewModel;

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

$studentId = $_GET[STUDENT_ID];
$classroomId = $_GET[CLASSROOM_ID];
$trimestreId = $_GET[TRIMESTRE_ID];

$config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);
$model = new ReportViewModel($trimestreId, $studentId, $classroomId);
$data = $model->getData();
$tplEngine = new PDFEngine($data);
$tplEngine->Output('I');

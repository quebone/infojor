<?php
namespace tfg;

session_start();

error_reporting(E_ALL); ini_set('display_errors', 'On');
require_once 'init.php';
require_once 'vendor/fpdf/fpdf.php';
require_once 'vendor/simi/tplengine/PDFEngine.php';

if (isset($_SESSION[USER_ID])) {
	$userId = $_SESSION[USER_ID];
} else {
 	header('Location: login.php');
}

$studentId = $_SESSION['studentId'];
$classroomId = $_SESSION['classroomId'];

$config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);
$model = new \tfg\presentation\model\ReportViewModel($studentId, $classroomId);
$data = $model->getData();
$tplEngine = new \Simi\TplEngine\PDFEngine($data);
$tplEngine->Output('I');

<?php
namespace Infojor;

session_start();

error_reporting(E_ALL); ini_set('display_errors', 'On');
require_once 'init.php';
// require_once 'vendor/fpdf18/fpdf.php';
require_once 'vendor/simi/tplengine/PDFEngine.php';

if (isset($_SESSION['userid'])) {
	$userId = $_SESSION['userid'];
} else {
 	header('Location: login.php');
}

$studentId = $_SESSION['studentId'];
$classroomId = $_SESSION['classroomId'];

$config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_NEVER);
$viewModel = new \Infojor\Presentation\Model\ReportViewModel($entityManager, $studentId, $classroomId);
$data = $viewModel->getData();
$tplEngine = new \Simi\TplEngine\PDFEngine($data);
$tplEngine->Output('I');

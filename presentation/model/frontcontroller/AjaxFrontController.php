<?php
namespace Infojor\Presentation\Model\FrontController;

if (!defined('REQUIRED')) {
	define('BASEDIR', '../../');
// 	require_once BASEDIR.'init.php';
	require_once BASEDIR.'config/constants.php';
	require_once '../../service/MainService.php';
	require_once '../../service/SchoolService.php';
	require_once '../../service/UserService.php';
	require_once '../../service/EvaluationService.php';
	require_once '../../presentation/model/ViewModel.php';
	require_once '../../presentation/model/SchoolViewModel.php';
	require_once '../../presentation/model/UserViewModel.php';
	require_once '../../presentation/model/EvaluationViewModel.php';
	require_once '../../vendor/simi/tplengine/TplEngine.php';
}

class AjaxFrontController
{
	private $em;

	function __construct()
	{
		require BASEDIR.'bootstrap.php';
		$this->em = $entityManager;
	}

	public function login()
	{
		$viewModel = new \Infojor\Presentation\Model\UserViewModel(null, $this->em);
		return $viewModel->login();
	}
	
	public function setSession()
	{	
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		reset($_POST);
		$var = key($_POST);
		$value = $_POST[$var];
		$_SESSION[$var] = $value;
		return json_encode($_POST);
	}
	
	public function getEvaluationData()
	{
		if (!isset($_POST['studentId']) || !isset($_POST['classroomId'])) {
			return "error";
		}
		$studentId = $_POST['studentId'];
		$classroomId = $_POST['classroomId'];
		$viewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $this->em);
		$tplEngine = new \Simi\TplEngine\TplEngine();
		$evaluation = $viewModel->getEvaluations($studentId, $classroomId, false);
		$data = $tplEngine->output('createEvaluations', $evaluation);
		return json_encode($data, JSON_UNESCAPED_SLASHES);
	}
	
	public function setPartialEvaluation()
	{
		$studentId = $_POST['studentId'];
		$dimensionId = $_POST['dimensionId'];
		$markId = $_POST['markId'];
		$viewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $this->em);
		return $viewModel->setPartialEvaluation($studentId, $dimensionId, $markId);
	}
	
	public function setGlobalEvaluation()
	{
		$studentId = $_POST['studentId'];
		$areaId = $_POST['areaId'];
		$markId = $_POST['markId'];
		$viewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $this->em);
		return $viewModel->setGlobalEvaluation($studentId, $areaId, $markId);
	}
	
	public function setObservation()
	{
		$studentId = $_POST['studentId'];
		$text = $_POST['observation'];
		$viewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $this->em);
		return $viewModel->setObservation($studentId, $observation);
	}
	
	public function getThumbnail() {
		$studentId = $_POST['studentId'];
		$viewModel = new \Infojor\Presentation\Model\UserViewModel(null, $this->em);
		$thumbnail = $viewModel->getThumbnail($studentId);
		$path = THUMBNAILDIR;
		$fileName = $thumbnail . '.jpg';
		$type = pathinfo($path . $fileName, PATHINFO_EXTENSION);
		if (!file_exists($path . $fileName)) {
			$fileName = AVATAR;
		}
		$data = file_get_contents($path . $fileName);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
}

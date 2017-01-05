<?php
use tfg\service\SchoolService;
use tfg\presentation\model\EvaluationViewModel;

require "init.php";

function test1() {
	$studentId = 54;
	$model = new EvaluationViewModel();
	$evaluation = $model->getEvaluations($studentId, null, null, true);
	$tplEngine = new \Simi\TplEngine\TplEngine();
	$data = $tplEngine->output('createEvaluations', $evaluation);
	return json_encode($data, JSON_UNESCAPED_SLASHES);
}

function test2() {
// 	require_once BASEDIR.'PHPMailer/PHPMailerAutoload.php';
	$model = new SchoolService();
	$model->sendMessage(1, "patata\r\nbullida");
}

test1();
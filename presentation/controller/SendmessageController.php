<?php
namespace infojor\presentation\controller;

use infojor\service\SchoolService;

class SendmessageController extends Controller
{
	public function sendMessage()
	{
		session_start();
 		$model = new SchoolService();
 		$userId = $_SESSION[USER_ID];
 		$message = $_POST['message'];
		return $model->sendMessage($userId, $message);
	}
}
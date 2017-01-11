<?php

use tfg\presentation\model\SchoolViewModel;
use tfg\service\UserService;
use tfg\presentation\controller\DimensionsController;
use tfg\service\SchoolService;
use tfg\presentation\model\UserViewModel;

require "init.php";

function test1() {
	$model = new UserViewModel();
	$data = $model->listAllTeachers();
	$userId = 437;
	$data = $model->removeTeacher($data, $userId);
	var_dump($data);
}

test1();

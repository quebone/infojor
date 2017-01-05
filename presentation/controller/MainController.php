<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\UserViewModel;

class MainController extends Controller
{
	public function getSectionData($userId)
	{
		$userViewModel = new UserViewModel();
		return $userViewModel->getCurrentSections($userId);
	}
}
<?php
namespace Infojor\Presentation\Model\FrontController;

class MainFrontController extends FrontController
{
	private $userId;
	
	public function __construct($userId, \Doctrine\ORM\EntityManager $em)
	{
		parent::__construct($em);
		$this->userId = $userId;
	}
	
	public function getSectionData()
	{
		$userModel = new \Infojor\Service\UserService($this->em);
		$userViewModel = new \Infojor\Presentation\Model\UserViewModel($userModel, $this->em);
		$sections = $userViewModel->getCurrentSections($this->userId);
		return $sections;
	}
}
<?php
namespace Infojor\Presentation\Model\FrontController;

class LoginFrontController extends FrontController
{
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		parent::__construct($em);
	}
}
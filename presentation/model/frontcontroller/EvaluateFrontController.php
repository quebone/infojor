<?php
namespace Infojor\Presentation\Model\FrontController;

class EvaluateFrontController extends FrontController
{
	private $userId;
	private $classroomId;
	private $areaId;
	private $studentId;
	private $userViewModel;
	private $schoolViewModel;
	private $evaluationViewModel;
	
	public function __construct($userId, $studentId, $classroomId, $areaId, \Doctrine\ORM\EntityManager $em)
	{
		parent::__construct($em);
		$this->userId = $userId;
		$this->classroomId = $classroomId;
		$this->areaId = $areaId;
		$this->studentId = $studentId;
		$this->createViewModels();
		$this->getStudents();
	}
	
	private function createViewModels()
	{
		$userModel = new \Infojor\Service\UserService($this->em);
		$this->userViewModel = new \Infojor\Presentation\Model\UserViewModel($userModel, $this->em);
		$schoolModel = new \Infojor\Service\SchoolService($this->em);
		$this->schoolViewModel = new \Infojor\Presentation\Model\SchoolViewModel($schoolModel, $this->em);
		$evaluationModel = new \Infojor\Service\EvaluationService($this->em);
		$this->evaluationViewModel = new \Infojor\Presentation\Model\EvaluationViewModel($evaluationModel, $this->em);
	}
	
	private function getStudents()
	{
		$students = $this->schoolViewModel->getCurrentClassroomStudents($this->classroomId);
		$this->addData($students);
	}
	
	public function getEvaluationData($includeSpecialities = false): array
	{
		return $this->evaluationViewModel->getEvaluations($this->studentId, $this->classroomId, $includeSpecialities);
	}
}
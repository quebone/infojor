<?php
namespace Infojor\Presentation\Model;

require_once 'vendor/fpdf181/fpdf.php';

final class ReportViewModel extends ViewModel {
	private $userModel;
	private $schoolModel;
	
	public function __construct(\Doctrine\ORM\EntityManager $entityManager) {
		parent::__construct(null, $entityManager);
		$this->userModel = new \Infojor\Service\UserService($entityManager);
		$this->schoolModel = new \Infojor\Service\SchoolService($entityManager);
	}

	
	public function getData()
	{
		$studentId = 32;
// 		$studentId = 44;
		$classroomId = 15;
		return  $this->getClassroomStudents($classroomId);
		// 		return $this->getStudent($studentId);
	}
	
	public function getClassroomStudents($classroomId)
	{
		$students = $this->schoolModel->getCurrentClassroomStudents($classroomId);
		$data = array();
		foreach ($students as $student) {
			$data[$student->getId()] = $this->getStudent($student->getId());
		}
		return $data;
	}
	
	public function getStudent($studentId)
	{
		$data = array();
		
		//global header
		$data['header']['logo_gencat'] = IMAGEDIR.'logos/logo-gencat.png';
		$data['header']['logo_jor'] = IMAGEDIR.'logos/logo-jor.png';
		
		//first page header
		$student = $this->userModel->getStudent($studentId);
		$course = $this->schoolModel->getActiveCourse();
		$trimestre = $this->schoolModel->getActiveTrimestre();
		$classroom = $student->getClassroom($course, $trimestre);
		$tutorings = $classroom->getTutors($course, $trimestre);
		$degree = $classroom->getLevel()->getCycle()->getDegree();
		$data['title'] = "Informe d'avaluació ";
		$data['degree']['id'] = $degree->getId();
		$data['degree']['name'] = "d'" . $degree->getName();
		$data['student'] =  $student->getName() . " " . $student->getSurnames();
		$data['trimestre'] = 'Trimestre ' . $trimestre->getNumber();
		$data['course'] = 'Curs ' . $course->getYear();
		$data['classroom'] = $classroom->getName();
		$data['tutor'] = '';
		foreach ($tutorings as $tutoring) {
			$teacher = $tutoring->getTeacher();
			$data['tutor'] .= (strlen($data['tutor']) > 0 ? " / " : "") . $teacher->getName() . " " . $teacher->getSurnames();
		}
		
		//main (evaluations)
		$scopes = $degree->getScopes();
		foreach ($scopes as $scope) {
			$scopeId = $scope->getId();
			$data['scopes'][$scopeId]['name'] = $scope->getName();
			$data['scopes'][$scopeId]['areas'] = array();
			foreach ($scope->getAreas() as $area) {
				$areaId = $area->getId();
				$data['scopes'][$scope->getId()]['areas'][$areaId]['name'] = $area->getName();
				$data['scopes'][$scopeId]['areas'][$areaId]['dimensions'] = array();
				foreach ($area->getDimensions() as $dimension) {
					$dimensionId = $dimension->getId();
					$data['scopes'][$scopeId]['areas'][$areaId]['dimensions'][$dimensionId]['name'] = $dimension->getName();
					$data['scopes'][$scopeId]['areas'][$areaId]['dimensions'][$dimensionId]['description'] = $dimension->getDescription();
					$data['scopes'][$scopeId]['areas'][$areaId]['dimensions'][$dimensionId]['mark'] = '';
					$pe = $dimension->getPartialEvaluation($student, $course, $trimestre);
					if ($pe != null ) {
						$mark = $pe->getPartialEvaluationDescription()->getMark();
						$data['scopes'][$scopeId]['areas'][$areaId]['dimensions'][$dimensionId]['mark'] = $mark;
					}
				}
				$ge = $area->getGlobalEvaluation($student, $course, $trimestre);
				$data['scopes'][$scope->getId()]['areas'][$areaId]['mark'] = '';
				if ($ge != null) {
					$mark = $ge->getGlobalEvaluationDescription()->getMark();
					$data['scopes'][$scope->getId()]['areas'][$areaId]['mark'] = $mark;
				}
			}
		}
		$reinforceClassrooms = $this->schoolModel->getSchool()->getReinforceClassrooms();
		$data['observation']['title'] = 'Observacions';
		$data['observation']['text'] = '';
		if ($student->getCourseObservation($course, $trimestre) != null) {
			$data['observation']['text'] = $student->getCourseObservation($course, $trimestre)->getText();
		}
		$data['reinforce']['title'] = '';
		$data['reinforce']['text'] = '';
		foreach ($reinforceClassrooms as $reinforceClassroom) {
			$data['reinforce']['title'] .= strlen($data['reinforce']['title']) > 0 ? " i " : '';
			$data['reinforce']['title'] .= $reinforceClassroom->getName();
			$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
			$data['reinforce']['text'] .= strlen($data['reinforce']['text']) > 0 ? "\n" : '';
			if ($observation != null) {
				$data['reinforce']['text'] .= $observation->getText();
			}
		}
		
		//footer
		$data['footer']['caption'] = "<B>Qualificacions</B>. Parcial: <B>A</B> (Alt), <B>MA</B> (Mitjà Alt), <B>M</B> (Mitjà), <B>MB</B> (Mitjà Baix), <B>B</B> (Baix). Globals: <B>AE</B> (Assoliment Excel·lent), <B>AN</B> (Assoliment Notable), <B>AS</B> (Assoliment Satisfactori), <B>NA</B> (No Assoliment).";
		$data['footer']['page'] = '';
		$data['footer']['student'] = $student->getName() . " " . $student->getSurnames();
		return $data;
	}
}
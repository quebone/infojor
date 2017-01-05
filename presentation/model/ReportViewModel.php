<?php
namespace tfg\presentation\model;

require_once BASEDIR.'vendor/fpdf/fpdf.php';

final class ReportViewModel extends MainViewModel implements IReportViewModel {
	private $student;
	private $classroom;
	private $userModel;
	private $schoolModel;
	
	public function __construct($student = null, $classroom = null) {
		parent::__construct();
		$this->userModel = new \tfg\service\UserService();
		$this->schoolModel = new \tfg\service\SchoolService();
		$this->student = $student;
		$this->classroom = $classroom;
	}

	public function getData()
	{
		$data = null;
		if ($this->classroom != null) {
			$data = $this->getClassroomReport($this->classroom);
		} else if ($this->student != null) {
			$data[$this->student] = $this->getStudentReport($this->student);
		}
		return $data;
	}
	
	private function getClassroomReport($classroomId)
	{
		$students = $this->schoolModel->getCurrentClassroomStudents($classroomId);
		$data = array();
		foreach ($students as $student) {
			$data[$student->getId()] = $this->getStudentReport($student->getId());
		}
		return $data;
	}
	
	private function getStudentReport($studentId)
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
		
		//observations
		
		$data['observation']['title'] = 'Observacions';
		$data['observation']['text'] = '';
		if ($student->getCourseObservation($course, $trimestre) != null) {
			$observationText = $student->getCourseObservation($course, $trimestre)->getText();
			$observationText = trim(stripcslashes($observationText),'"');
			$data['observation']['text'] = $observationText;
		}
		
		//reinforce classrooms
		$data['reinforce']['title'] = '';
		$data['reinforce']['text'] = '';
		$reinforceClassrooms = $this->schoolModel->getSchool()->getReinforceClassrooms();
		foreach ($reinforceClassrooms as $reinforceClassroom) {
			$data['reinforce']['title'] .= strlen($data['reinforce']['title']) > 0 ? " i " : '';
			$data['reinforce']['title'] .= $reinforceClassroom->getName();
			$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
			$data['reinforce']['text'] .= strlen($data['reinforce']['text']) > 0 ? "\n" : '';
			if ($observation != null) {
				$data['reinforce']['text'] .= stripslashes($observation->getText());
			}
		}
		
		//eliminem els elements sense valoració
		$data['scopes'] = $this->removeEmptyFields($data['scopes']);
		
		//footer. A educació infantil no hi incloem les valoracions globals
		$data['footer']['caption'] = "<B>Qualificacions</B>. Parcial: <B>A</B> (Alt), <B>MA</B> (Mitjà Alt), <B>M</B> (Mitjà), <B>MB</B> (Mitjà Baix), <B>B</B> (Baix).";
		if ($degree->getId() == 2) {	//primària
			$data['footer']['caption'] .= " Globals: <B>AE</B> (Assoliment Excel·lent), <B>AN</B> (Assoliment Notable), <B>AS</B> (Assoliment Satisfactori), <B>NA</B> (No Assoliment).";
		}
		$data['footer']['page'] = '';
		$data['footer']['student'] = $student->getName() . " " . $student->getSurnames();
		return $data;
	}
	
	private function removeEmptyFields($data):array
	{
		$cleanedData = array();
		foreach ($data as $scopeId=>$scope) {
			foreach ($scope['areas'] as $areaId=>$area) {
				$dataFound = false;
				foreach ($area['dimensions'] as $dimId=>$dimension) {
					if ($dimension['mark'] != '') {
						$cleanedData[$scopeId]['areas'][$areaId]['dimensions'][$dimId] = $dimension;
						$dataFound = true;
					}
				}
				if ($area['mark'] != '') {
					$cleanedData[$scopeId]['areas'][$areaId]['mark'] = $area['mark'];
					$dataFound = true;
				}
				if ($dataFound) {
					$cleanedData[$scopeId]['areas'][$areaId]['name'] = $area['name'];
				}
			}
			if (isset($cleanedData[$scopeId])) {
				$cleanedData[$scopeId]['name'] = $scope['name'];
			}
		}
		return $cleanedData;
	}
}
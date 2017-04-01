<?php
namespace infojor\presentation\model;

use infojor\service\SchoolService;
use infojor\service\DAO;
use infojor\service\ReportService;

require_once BASEDIR.'vendor/fpdf/fpdf.php';
require_once BASEDIR.'vendor/PHPExcel/Classes/PHPExcel.php';

final class ReportViewModel extends MainViewModel {
	private $student;
	private $classroom;
	private $trimestre;
	
	public function __construct($trimestre, $student = null, $classroom = null) {
		parent::__construct();
		$this->trimestre = $trimestre;
		$this->student = $student;
		$this->classroom = $classroom;
	}

	public function getData()
	{
		$data = null;
		if ($this->classroom != null) {
			$data = $this->getClassroomReport();
		} else if ($this->student != null) {
			$data[$this->student] = $this->getStudentReport($this->student);
		}
		return $data;
	}
	
	/**
	 * Retorna les dades dels informes corresponents a tota una classe
	 */
	private function getClassroomReport()
	{
		$dao = new DAO();
		$classroom = $dao->getById("Classroom", $this->classroom);
		$course = $dao->getActiveCourse();
		$trimestre = $dao->getById("Trimestre", $this->trimestre);
		$students = $dao->getSchool()->getClassroomStudents($classroom, $course, $trimestre);
		$data = array();
		foreach ($students as $student) {
			$data[$student->getId()] = $this->getStudentReport($student->getId());
		}
		return $data;
	}
	
	/**
	 * Retorna les dades dels informes corresponents a un alumne
	 */
	private function getStudentReport($studentId)
	{
		$data = array();
		
		//global header
		$data['header']['logo_gencat'] = IMAGEDIR.'logos/logo-gencat.png';
		$data['header']['logo_jor'] = IMAGEDIR.'logos/logo-jor.png';
		
		//first page header
		$student = $this->dao->getById("Student", $studentId);
		$course = $this->dao->getActiveCourse();
		$trimestre = $this->dao->getById("Trimestre", $this->trimestre);
		$classroom = $student->getClassroom($course, $trimestre);
		$tutorings = $classroom->getTutors($course, $trimestre);
		$cycle = $classroom->getLevel()->getCycle();
		$degree = $cycle->getDegree();
		
		$data['title'] = "Informe d'avaluació ";
		$data['degree']['id'] = $degree->getId();
		$data['degree']['name'] = "d'" . $degree->getName();
		$data['student'] =  $student->getName() . " " . $student->getSurnames();
		$data['trimestre'] = $trimestre->getNumber();
		$data['course'] = 'Curs ' . $course->getYear();
		$data['classroom'] = $classroom->getName();
		$data['tutor'] = '';
		foreach ($tutorings as $tutoring) {
			$teacher = $tutoring->getTeacher();
			$data['tutor'] .= (strlen($data['tutor']) > 0 ? " / " : "") . $teacher->getName() . " " . $teacher->getSurnames();
		}
		
		//main (evaluations)
		
		//scopes
		$evaluationViewModel = new EvaluationViewModel();
		$data['scopes'] = $evaluationViewModel->getScopesData($studentId, $classroom, null);
		//eliminem els elements sense valoració
		$data['scopes'] = $this->removeEmptyFields($data['scopes']);
		
		//observations
		$data['observation']['title'] = 'Observacions';
		$data['observation']['text'] = '';
		$observation = $student->getCourseObservation($course, $trimestre);
		if ( $observation != null) {
			$observationText = $observation->getText();
			$observationText = trim(stripcslashes($observationText),'"');
			$data['observation']['text'] = $observationText;
		}
		
		//reinforce classrooms
		$data['reinforce']['title'] = '';
		$data['reinforce']['text'] = '';
		$reinforceClassrooms = $this->dao->getSchool()->getReinforceClassrooms();
		foreach ($reinforceClassrooms as $reinforceClassroom) {
			$data['reinforce']['title'] .= strlen($data['reinforce']['title']) > 0 ? " i " : '';
			$data['reinforce']['title'] .= $reinforceClassroom->getName();
			$observation = $student->getCourseObservation($course, $trimestre, $reinforceClassroom);
			$data['reinforce']['text'] .= strlen($data['reinforce']['text']) > 0 ? "\n" : '';
			if ($observation != null) {
				$data['reinforce']['text'] .= stripcslashes($observation->getText());
			}
		}
		
		//footer. A educació infantil no hi incloem les valoracions globals
		$data['footer']['caption'] = "<B>Qualificacions</B>. Parcial: <B>A</B> (Alt), <B>MA</B> (Mitjà Alt), <B>M</B> (Mitjà), <B>MB</B> (Mitjà Baix), <B>B</B> (Baix).";
		if ($degree->getId() == 2) {	//primària
			$data['footer']['caption'] .= " Globals: <B>AE</B> (Assoliment Excel·lent), <B>AN</B> (Assoliment Notable), <B>AS</B> (Assoliment Satisfactori), <B>NA</B> (No Assoliment).";
		}
		$data['footer']['page'] = '';
		$data['footer']['student'] = $student->getName() . " " . $student->getSurnames();
		return $data;
	}
	
	/**
	 * Els camps sense valors no han de sortir als informes
	 */
	private function removeEmptyFields($data):array
	{
		$cleanedData = array();
		$at = $this->dao->getById("Trimestre", $this->trimestre);
		foreach ($data as $scopeId=>$scope) {
			foreach ($scope['areas'] as $areaId=>$area) {
				$dataFound = false;
				foreach ($area['dimensions'] as $dimId=>$dimension) {
					$markFound = false;
					for ($i = 1; $i <= $at->getNumber(); $i++) {
						if ($dimension['pes'][$i]['mark'] != '') $markFound = true;
					}
					if ($markFound) {
						$cleanedData[$scopeId]['areas'][$areaId]['dimensions'][$dimId] = $dimension;
						$dataFound = true;
					}
				}
				for ($i = 1; $i <= $at->getNumber(); $i++) {
					if ($area['ges'][$i]['mark'] != '') {
						$cleanedData[$scopeId]['areas'][$areaId]['ges'][$i]['mark'] = $area['ges'][$i]['mark'];
						$dataFound = true;
					}
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
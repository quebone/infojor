<?php
namespace infojor\service;

final class StatisticsService extends MainService
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getSummaryTableData($classroomId, $courseId, $trimestreId)
	{
		$classroom = $this->dao->getById("Classroom", $classroomId);
		$course = $this->dao->getById("Course", $courseId);
		$trimestre = $this->dao->getById("Trimestre", $trimestreId);
		$students = $classroom->getStudents($course);
		$level = $classroom->getLevel();
		$cycle = $level->getCycle();
		$degree = $cycle->getDegree();
		$scopes = $degree->getScopes();
		$areas = array();
		foreach ($scopes as $scope) {
			foreach ($scope->getAreas() as $area) {
				array_push($areas, $area);
			}
		}
		$marks = array();
		foreach ($students as $student) {
			foreach ($areas as $area) {
				array_push($marks, $student->getAreaEvaluation($area, $course, $trimestre));
			}
		}
		return array("students" => $students, "areas" => $areas, "marks" =>$marks);
	}
}
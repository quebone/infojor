<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="globalevaluations")
 **/
class GlobalEvaluation extends Evaluation
{
	/**
	 * @ManyToOne(targetEntity="Student", inversedBy="globalEvaluations")
	 * @JoinColumn(name="student_id", referencedColumnName="id")
	 */
	private $student;
	/**
	 * @ManyToOne(targetEntity="Course", inversedBy="globalEvaluations")
	 * @JoinColumn(name="course_id", referencedColumnName="id")
	 */
	private $course;
	/**
	 * @ManyToOne(targetEntity="Trimestre", inversedBy="globalEvaluations")
	 * @JoinColumn(name="trimestre_id", referencedColumnName="number")
	 */
	private $trimestre;
	/**
	 * @ManyToOne(targetEntity="Area", inversedBy="globalEvaluations")
	 * @JoinColumn(name="area_id", referencedColumnName="id")
	 */
	private $area;
	/**
	 * @ManyToOne(targetEntity="Scope", inversedBy="globalEvaluations")
	 * @JoinColumn(name="scope_id", referencedColumnName="id")
	 */
	private $scope;
	/**
	 * @ManyToOne(targetEntity="GlobalEvaluationDescription", inversedBy="globalEvaluations")
	 * @JoinColumn(name="globalevaluationdescription_id", referencedColumnName="id")
	 */
	private $globalEvaluationDescription;
	
	public function __construct(
			Student $student,
			Course $course,
			Trimestre $trimestre,
			Area $area,
			GlobalEvaluationDescription $ged) {
		$this->student = $student;
		$this->course = $course;
		$this->trimestre = $trimestre;
		$this->area = $area;
		$this->globalEvaluationDescription = $ged;
	}
	
	public function getStudent() {
		return $this->student;
	}
	
	public function setStudent($student) {
		$this->student = $student;
	}
	
	public function getCourse() {
		return $this->course;
	}
	
	public function setCourse($course) {
		$this->course = $course;
	}
	
	public function getTrimestre() {
		return $this->trimestre;
	}
	
	public function setTrimestre($trimestre) {
		$this->trimestre = $trimestre;
	}
	
	public function getArea() {
		return $this->area;
	}
	
	public function setArea($area) {
		$this->area = $area;
	}
	
	public function getScope() {
		return $this->scope;
	}
	
	public function setScope($scope) {
		$this->scope = $scope;
	}
	
	public function getGlobalEvaluationDescription() {
		return $this->globalEvaluationDescription;
	}
	
	public function setGlobalEvaluationDescription($globalevaluationdescription) {
		$this->globalEvaluationDescription = $globalevaluationdescription;
	}
	
	public function getMark() {
		return $this->globalEvaluationDescription->getMark();
	}
}

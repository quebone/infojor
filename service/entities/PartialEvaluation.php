<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="partialevaluations")
 **/
class PartialEvaluation extends Evaluation
{
	/**
	 * @ManyToOne(targetEntity="Student", inversedBy="partialEvaluations")
	 * @JoinColumn(name="student_id", referencedColumnName="id")
	 */
	private $student;

	/**
	 * @ManyToOne(targetEntity="Course", inversedBy="partialEvaluations")
	 * @JoinColumn(name="course_id", referencedColumnName="year")
	 */
	private $course;

	/**
	 * @ManyToOne(targetEntity="Trimestre", inversedBy="partialEvaluations")
	 * @JoinColumn(name="trimestre_id", referencedColumnName="number")
	 */
	private $trimestre;

	/**
	 * @ManyToOne(targetEntity="Dimension", inversedBy="partialEvaluations")
	 * @JoinColumn(name="dimension_id", referencedColumnName="id")
	 */
	private $dimension;

	/**
	 * @ManyToOne(targetEntity="PartialEvaluationDescription", inversedBy="partialEvaluations")
	 * @JoinColumn(name="partialevaluationdescription_id", referencedColumnName="id")
	 */
	private $partialEvaluationDescription;
	
	public function __construct(Student $student, Course $course, Trimestre $trimestre, Dimension $dimension, PartialEvaluationDescription $ped) {
		$this->student = $student;
		$this->course = $course;
		$this->trimestre = $trimestre;
		$this->dimension = $dimension;
		$this->partialEvaluationDescription = $ped;
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
	
	public function getDimension() {
		return $this->dimension;
	}
	
	public function setDimension($dimension) {
		$this->dimension = $dimension;
	}

	public function getPartialEvaluationDescription() {
		return $this->partialEvaluationDescription;
	}
	
	public function setPartialEvaluationDescription(PartialEvaluationDescription $partialevaluationdescription) {
		$this->partialEvaluationDescription = $partialevaluationdescription;
	}
	
	public function getMark() {
		return $this->partialEvaluationDescription->getMark();
	}
}

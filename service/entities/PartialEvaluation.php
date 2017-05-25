<?php
namespace infojor\service\Entities;

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
	 * @ManyToOne(targetEntity="Teacher", inversedBy="partialEvaluations")
	 * @JoinColumn(name="teacher_id", referencedColumnName="id")
	 */
	private $teacher;
	/**
	 * @ManyToOne(targetEntity="Course", inversedBy="partialEvaluations")
	 * @JoinColumn(name="course_id", referencedColumnName="id")
	 */
	private $course;
	/**
	 * @ManyToOne(targetEntity="Trimestre", inversedBy="partialEvaluations")
	 * @JoinColumn(name="trimestre_id", referencedColumnName="number", nullable=true)
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
	
	public function __construct(
			Student $student,
			Teacher $teacher,
			Course $course,
			$trimestre,
			Dimension $dimension,
			PartialEvaluationDescription $ped) {
		$this->student = $student;
		$this->teacher = $teacher;
		$this->course = $course;
		$this->trimestre = $trimestre;
		$this->dimension = $dimension;
		$this->partialEvaluationDescription = $ped;
	}
	
	public function getStudent() {
		return $this->student;
	}
	
	public function setStudent(Student $student) {
		$this->student = $student;
	}
	
	public function getTeacher() {
		return $this->teacher;
	}
	
	public function setTeacher(Teacher $teacher) {
		$this->teacher = $teacher;
	}
	
	public function getCourse() {
		return $this->course;
	}
	
	public function setCourse(Course $course) {
		$this->course = $course;
	}
	
	public function getTrimestre() {
		return $this->trimestre;
	}
	
	public function setTrimestre(Trimestre $trimestre) {
		$this->trimestre = $trimestre;
	}
	
	public function getDimension() {
		return $this->dimension;
	}
	
	public function setDimension(Dimension $dimension) {
		$this->dimension = $dimension;
	}

	public function getPartialEvaluationDescription() {
		return $this->partialEvaluationDescription;
	}
	
	public function setPartialEvaluationDescription(PartialEvaluationDescription $ped) {
		$this->partialEvaluationDescription = $ped;
	}
	
	public function getMark() {
		return $this->partialEvaluationDescription->getMark();
	}
}

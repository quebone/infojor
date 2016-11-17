<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="observations")
 **/
class Observation {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=512) **/
	private $text;
	/**
	 * @ManyToOne(targetEntity="Course", inversedBy="observations")
	 * @JoinColumn(name="course_id", referencedColumnName="year")
	 */
	private $course;
	/**
	 * @ManyToOne(targetEntity="Trimestre", inversedBy="observations")
	 * @JoinColumn(name="trimestre_id", referencedColumnName="number")
	 */
	private $trimestre;
	/**
	 * @ManyToOne(targetEntity="ReinforceClassroom", inversedBy="observations")
	 * @JoinColumn(name="reinforceclassroom_id", referencedColumnName="id")
	 */
	private $reinforceClassroom;
	/**
	 * @ManyToOne(targetEntity="Student", inversedBy="observations")
	 * @JoinColumn(name="student_id", referencedColumnName="id")
	 */
	private $student;
	
	public function __construct($text, $student, $course, $trimestre, $reinforceClassroom = null) {
		$this->text = $text;
		$this->student = $student;
		$this->course = $course;
		$this->trimestre = $trimestre;
		$this->reinforceClassroom = $reinforceClassroom;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function setText($text) {
		$this->text = $text;
	}
	
	public function getCourse() {
		return $this->course;
	}
	
	public function getTrimestre() {
		return $this->trimestre;
	}
	
	public function getReinforceClassroom() {
		return $this->reinforceClassroom;
	}
	
	public function getStudent() {
		return $this->student;
	}
	
	public function setStudent(Student $student) {
		$this->student = $student;
	}
}
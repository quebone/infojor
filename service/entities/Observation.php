<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="observations")
 **/
class Observation {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=256) **/
	private $text;
	/**
	 * @ManyToOne(targetEntity="Course", inversedBy="observations")
	 * @JoinColumn(name="course_id", referencedColumnName="year")
	 */
	private $course;
	/**
	 * @ManyToOne(targetEntity="Student", inversedBy="observations")
	 * @JoinColumn(name="student_id", referencedColumnName="id")
	 */
	private $student;
	
	public function __construct($text) {
		$this->text = $text;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function setText() {
		return $this->text;
	}
	
	public function getCourse() {
		return $this->course;
	}
	
	public function getStudent() {
		return $this->student;
	}
	
	public function setStudent(Student $student) {
		$this->student = $student;
	}
}
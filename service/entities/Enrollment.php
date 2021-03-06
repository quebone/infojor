<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="enrollments")
 **/
class Enrollment
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/**
	 * @ManyToOne(targetEntity="Student", inversedBy="enrollments")
	 * @JoinColumn(name="student_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $student;
	/**
	 * @ManyToOne(targetEntity="Classroom", inversedBy="enrollments")
	 * @JoinColumn(name="classroom_id", referencedColumnName="id")
	 */
	private $classroom;
	/**
	 * @ManyToOne(targetEntity="Course")
	 * @JoinColumn(name="course_id", referencedColumnName="id")
	 */
	private $course;
	/**
	 * @ManyToOne(targetEntity="Trimestre")
	 * @JoinColumn(name="trimestre_id", referencedColumnName="number")
	 */
	private $trimestre;
	
	public function __construct(Student $student, Classroom $classroom, Course $course, Trimestre $trimestre) {
		$this->student = $student;
		$this->classroom = $classroom;
		$this->course = $course;
		$this->trimestre = $trimestre;
	}
	
	public function getStudent()
	{
		return $this->student;
	}

	public function setStudent($student)
	{
		$this->student = $student;
	}

	public function getClassroom()
	{
		return $this->classroom;
	}

	public function setClassroom($classroom)
	{
		$this->classroom = $classroom;
	}

	public function getCourse()
	{
		return $this->course;
	}

	public function setCourse($course)
	{
		$this->course = $course;
	}

	public function getTrimestre()
	{
		return $this->trimestre;
	}

	public function setTrimestre($trimestre)
	{
		$this->trimestre = $trimestre;
	}
}

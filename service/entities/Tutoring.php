<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="tutorings")
 **/
class Tutoring
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/**
	 * @ManyToOne(targetEntity="Teacher", inversedBy="tutorings")
	 * @JoinColumn(name="teacher_id", referencedColumnName="id")
	 */
	private $teacher;
	/**
	 * @ManyToOne(targetEntity="Classroom", inversedBy="tutors"))
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
	
	public function __construct(Teacher $teacher, Classroom $classroom, Course $course, Trimestre $trimestre=null)
	{
		$this->teacher = $teacher;
		$this->classroom = $classroom;
		$this->course = $course;
// 		$this->trimestre = $trimestre;
	}
	
	public function getTeacher()
	{
		return $this->teacher;
	}

	public function setTeacher($teacher)
	{
		$this->teacher = $teacher;
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

<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="reinforcings")
 **/
class Reinforcing
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;

	/**
	 * @ManyToOne(targetEntity="Teacher", inversedBy="reinforcings")
	 * @JoinColumn(name="teacher_id", referencedColumnName="id")
	 */
	private $teacher;
	
	/**
	 * @ManyToOne(targetEntity="ReinforceClassroom")
	 * @JoinColumn(name="reinforceclassroom_id", referencedColumnName="id")
	 */
	private $reinforceClassroom;

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
	
	public function __construct(Teacher $teacher, ReinforceClassroom $classroom, Course $course, Trimestre $trimestre)
	{
		$this->teacher = $teacher;
		$this->reinforceClassroom = $classroom;
		$this->course = $course;
		$this->trimestre = $trimestre;
	}
	
	public function getTeacher()
	{
		return $this->teacher;
	}

	public function setTeacher($teacher)
	{
		$this->teacher = $teacher;
	}

	public function getReinforceClassroom()
	{
		return $this->reinforceClassroom;
	}

	public function setReinforceClassroom($reinforceClassroom)
	{
		$this->reinforceClassroom = $reinforceClassroom;
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

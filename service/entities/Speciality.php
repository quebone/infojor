<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="specialities")
 **/
class Speciality
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;

	/**
	 * @ManyToOne(targetEntity="Teacher", inversedBy="specialities")
	 * @JoinColumn(name="teacher_id", referencedColumnName="id")
	 */
	private $teacher;
	
	/**
	 * @ManyToOne(targetEntity="Area")
	 * @JoinColumn(name="area_id", referencedColumnName="id")
	 */
	private $area;

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
	
	public function getTeacher()
	{
		return $this->teacher;
	}

	public function setTeacher($teacher)
	{
		$this->teacher = $teacher;
	}

	public function getArea()
	{
		return $this->area;
	}

	public function setArea($area)
	{
		$this->area = $area;
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

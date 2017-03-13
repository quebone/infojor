<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="courses")
 **/
class Course
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=9, unique=true) **/
	private $year;
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="id")
	 */
	private $globalEvaluations;
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="id")
	 */
	private $partialEvaluations;
	/**
	 * @OneToMany(targetEntity="Observation", mappedBy="id")
	 */
	private $observations;
	
	public function __construct($year) {
		$this->year = $year;
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getYear()
	{
		return $this->year;
	}
	
	public function setYear($year)
	{
		$this->year = $year;
	}
}
<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="courses")
 **/
class Course
{
	/** @Id @Column(type="string", length=9) **/
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
	
	public function __construct() {
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
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
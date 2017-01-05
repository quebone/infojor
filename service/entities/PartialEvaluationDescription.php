<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="partialevaluationdescriptions")
 **/
class PartialEvaluationDescription extends EvaluationDescription
{
	/** @Column(type="string", length=2) **/
	private $mark;
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="id")
	 */
	private $partialEvaluations;
	
	public function __construct() {
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getMark() {
		return $this->mark;
	}

	public function setMark($mark) {
		$this->mark = $mark;
	}
	
	public function getPartialEvaluations() {
		return $this->partialEvaluations;
	}
}

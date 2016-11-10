<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="globalevaluationdescriptions")
 **/
class GlobalEvaluationDescription extends EvaluationDescription
{
	/** @Column(type="string", length=2) **/
	private $mark;
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="id")
	 */
	private $globalEvaluations;
	
	public function __construct() {
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getMark() {
		return $this->mark;
	}
	
	public function setMark($mark) {
		$this->mark = $mark;
	}
	
	public function getGlobalEvaluations() {
		return $this->globalEvaluations;
	}
}

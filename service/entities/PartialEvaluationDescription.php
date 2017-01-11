<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="partialevaluationdescriptions")
 **/
class PartialEvaluationDescription extends EvaluationDescription
{
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="id")
	 */
	private $partialEvaluations;
	
	public function __construct() {
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getEvaluations() {
		return $this->partialEvaluations;
	}
}

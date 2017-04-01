<?php
namespace infojor\service\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="trimestres")
 **/
class Trimestre
{
	/** @Id @Column(type="integer") **/
	private $number;
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="id")
	 */
	private $globalEvaluations;
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="id")
	 */
	private $partialEvaluations;
	
	public function __construct() {
		$this->globalEvaluations = new ArrayCollection();
		$this->partialEvaluations = new ArrayCollection();
	}
	
	public function getNumber()
	{
		return $this->number;
	}
	
	public function setNumber($number)
	{
		$this->number = $number;
	}
	
	public function toArray()
	{
		$data = array();
		$data['number'] = $this->number;
		return $data;
	}
}
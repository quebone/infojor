<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="scopes")
 **/
class Scope {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50) **/
	private $name;
	/** @Column(type="string", length=256) **/
	private $description;
	/**
	 * @ManyToOne(targetEntity="Degree", inversedBy="scopes")
	 * @JoinColumn(name="degree_id", referencedColumnName="id")
	 */
	private $degree;
	/**
	 * @OneToMany(targetEntity="Area", mappedBy="scope")
	 */
	private $areas;
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="scope")
	 */
	private $globalEvaluations;
	
	public function __construct() {
		$this->areas = new \Doctrine\Common\Collections\ArrayCollection();
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getDegree() {
		return $this->degree;
	}
	
	public function getAreas() {
		return $this->areas;
	}
	
	public function getGobalEvaluations() {
		return $this->globalEvaluations;
	}
}

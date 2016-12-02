<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="dimensions")
 **/
class Dimension {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=256) **/
	private $name;
	/** @Column(type="string", length=256) **/
	private $description;
	/** @Column(type="boolean", options={"default": true}) **/
	private $active;
	/**
	 * @ManyToOne(targetEntity="Area", inversedBy="areas")
	 * @JoinColumn(name="area_id", referencedColumnName="id")
	 */
	private $area;
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="dimension")
	 */
	private $partialEvaluations;
	/**
	 * @ManyToMany(targetEntity="Cycle", mappedBy="dimensions")
	 */
	private $cycles;
	
	
	public function __construct() {
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cycles = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function isActive() {
		return $this->active;
	}
	
	public function getCycles() {
		return $this->cycles;
	}
	
	public function getPartialEvaluation(Student $student, Course $course, Trimestre $trimestre) {
		foreach ($this->partialEvaluations as $pe) {
			if ($pe->getStudent() == $student && $pe->getCourse() == $course && $pe->getTrimestre() == $trimestre) {
				return $pe;
			}
		}
		return null;
	}
}
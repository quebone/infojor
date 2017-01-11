<?php
namespace tfg\service\Entities;

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
	 * @ManyToOne(targetEntity="Area", inversedBy="dimensions")
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
	
	
	public function __construct($name, $description, $active=true) {
		$this->name = $name;
		$this->description = $description;
		$this->active = $active;
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
	
	public function setActive($active) {
		$this->active = $active;
	}
	
	public function getArea() {
		return $this->area;
	}
	
	public function setArea(Area $area) {
		$this->area = $area;
	}
	
	public function getCycles() {
		return $this->cycles;
	}
	
	public function setCycles($cycles) {
		foreach ($cycles as $cycle)
			$this->cycles->add($cycle);
	}
	
	public function addCycle(Cycle $cycle) {
		if ($this->cycles->contains($cycle)) return;
		$this->cycles->add($cycle);
		$cycle->addDimension($this);
	}
	
	public function removeCycle(Cycle $cycle) {
		if (!$this->cycles->contains($cycle)) return;
		$this->cycles->removeElement($cycle);
		$cycle->removeDimension($this);
	}
	
	public function getPartialEvaluations() {
		return $this->partialEvaluations;
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
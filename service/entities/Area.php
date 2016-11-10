<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="areas")
 **/
class Area {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50) **/
	private $name;
	/** @Column(type="boolean") options={"default": false} **/
	private $speciality;
	/**
	 * @ManyToMany(targetEntity="Teacher", mappedBy="areas")
	 */
	private $teachers;
	/**
	 * @ManyToOne(targetEntity="Scope", inversedBy="areas")
	 * @JoinColumn(name="scope_id", referencedColumnName="id")
	 */
	private $scope;
	/**
	 * @OneToMany(targetEntity="Dimension", mappedBy="area")
	 */
	private $dimensions;
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="area")
	 */
	private $globalEvaluations;
	
	public function __construct() {
		$this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->dimensions = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getTeachers() {
		return $this->teachers;
	}
	
	public function getScope() {
		return $this->scope;
	}
	
	public function getDimensions(Cycle $cycle = null) {
		if ($cycle != null) {
			$filteredDimensions = new \Doctrine\Common\Collections\ArrayCollection();
			foreach ($this->dimensions as $dimension) {
				$cycles = $dimension->getCycles();
				foreach ($cycles as $dimensionCycle) {
					if ($cycle->getId() == $dimensionCycle->getId()) {
						$filteredDimensions->add($dimension);
					}
				}
			}
			return $filteredDimensions;
		}
		return $this->dimensions;
	}
	
	public function isSpeciality() {
		return $this->speciality;
	}
}
	

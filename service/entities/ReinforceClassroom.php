<?php
namespace infojor\service\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="reinforceclassrooms")
 **/
class ReinforceClassroom {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=20) **/
	private $name;
	/**
	 * @OneToMany(targetEntity="Observation", mappedBy="id")
	 */
	private $observations;
	/**
	 * @OneToMany(targetEntity="Reinforcing", mappedBy="reinforceClassroom")
	 */
	private $reinforcers;
	/**
	 * @ManyToOne(targetEntity="School", inversedBy="reinforceclassrooms")
	 * @JoinColumn(name="school_id", referencedColumnName="id")
	 */
	private $school;
	
	public function __construct() {
		$this->reinforcers = new ArrayCollection();
		$this->observations = new ArrayCollection();
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

	public function getObservations() {
		return $this->observations;
	}
	
	public function getReinforcers(Course $course, Trimestre $trimestre=null) {
		$teachers = new ArrayCollection();
		foreach ($this->reinforcers as $reinforcer) {
			if ($reinforcer->getCourse() == $course) {
				$teachers->add($reinforcer);
			}
		}
		return $teachers;
	}
}
	

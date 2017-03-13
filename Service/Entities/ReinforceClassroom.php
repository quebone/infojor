<?php
namespace Infojor\Service\Entities;

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
	 * @ManyToMany(targetEntity="Teacher", mappedBy="reinforceclassrooms")
	 */
	private $teachers;
	/**
	 * @ManyToOne(targetEntity="School", inversedBy="reinforceclassrooms")
	 * @JoinColumn(name="school_id", referencedColumnName="id")
	 */
	private $school;
	
	public function __construct() {
		$this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getTeachers() {
		return $this->teachers;
	}
}
	
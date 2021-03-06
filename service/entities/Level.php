<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="levels")
 **/
class Level {
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=10) **/
	private $name;
	/**
	 * @OneToMany(targetEntity="Classroom", mappedBy="id")
	 */
	private $classrooms;
	/**
	 * @ManyToOne(targetEntity="Cycle", inversedBy="levels")
	 * @JoinColumn(name="cycle_id", referencedColumnName="id")
	 */
	private $cycle;

	public function __construct() {
		$this->classrooms = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getClassrooms() {
		return $this->classrooms;
	}
	
	public function getCycle():Cycle {
		return $this->cycle;
	}
	
	public function getScopes() {
		return $this->cycle->getScopes();
	}
}

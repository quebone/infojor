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
	 * @ManyToMany(targetEntity="Teacher", mappedBy="reinforceclassrooms")
	 */
	private $teachers;
	
	public function __construct() {
	  $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
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
}
	

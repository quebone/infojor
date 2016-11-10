<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="classrooms")
 **/
class Classroom
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=10) **/
	private $name;
	/**
	 * @ManyToOne(targetEntity="Level", inversedBy="classrooms")
	 * @JoinColumn(name="level_id", referencedColumnName="id")
	 */
	private $level;
	
	private $teachers;
	
	public function __construct() {
		$this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->levels = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getLevel():Level {
		return $this->level;
	}
	
	public function getTeachers() {
		return $this->teachers;
	}
	
	public function getScopes() {
		return $this->level->getScopes();
	}
}
	

<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="persons")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"person" = "Person", "teacher" = "Teacher", "student" = "Student"})
 */

class Person
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	/** @Column(type="string", length=40) **/
	protected $name;
	/** @Column(type="string", length=50) **/
	protected $surnames;
	/** @Column(type="string", length=50) **/
	protected $thumbnail;
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getSurnames() {
		return $this->surnames;
	}
	
	public function setSurnames($surnames) {
		$this->surnames = $surnames;
	}
	
	public function getThumbnail() {
		return $this->thumbnail;
	}

	public function setThumbnail($thumbnail) {
		$this->thumbnail = $thumbnail;
	}
}



<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="persons")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"person" = "Person", "teacher" = "Teacher", "student" = "Student"})
 */

abstract class Person
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	/** @Column(type="string", length=40) **/
	protected $name;
	/** @Column(type="string", length=50) **/
	protected $surnames;
	/** @Column(type="string", length=50) **/
	protected $thumbnail;
	/**
	 * @ManyToOne(targetEntity="School", inversedBy="persons")
	 * @JoinColumn(name="school_id", referencedColumnName="id")
	 */
	protected $school;
	
	public function __construct($name, $surnames, $thumbnail = null)
	{
		$this->name = $name;
		$this->surnames = $surnames;
		if ($thumbnail == null) $thumbnail = strtolower(str_replace(" ", "-", $surnames) . "-" . str_replace(" ", "-", $name));
		$this->thumbnail = $thumbnail;
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

	public function getSchool() {
		return $this->school;
	}
	
	public function setSchool(School $school) {
		$this->school = $school;
	}
}



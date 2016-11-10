<?php
namespace Infojor\Service\Entities;

/**
 * @Entity @Table(name="roles")
 **/
class Role
{
	const ADMIN = 'admin';
	const TUTOR = 'tutor';
	const SPECIALIST = 'specialist';
	const REINFORCETEACHER = 'reinforceteacher';
	
	/** @Id @Column(type="string", length=16) **/
	private $type;

	/**
	 * @ManyToMany(targetEntity="Teacher", mappedBy="roles")
	 */
	private $teachers;
	
	public function __construct() {
	  $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getType()
	{
		return $this->type;	
	}
}
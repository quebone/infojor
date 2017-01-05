<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="menus")
 **/
class Menu
{
	const BASIC = 1;
	const TEACHER = 2;
	const ADMIN = 3;
	const PRINT = 4;

	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=10) **/
	private $name;
	/**
	 * @OneToMany(targetEntity="MenuItem", mappedBy="menu")
	 */
	private $items;
	/**
	 *  @ManyToMany(targetEntity="Role", mappedBy="menus")
	**/
	private $roles;
	
	public function __construct()
	{
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function getRoless()
	{
		return $this->roles;
	}
}
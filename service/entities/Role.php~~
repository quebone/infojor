<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="roles")
 **/
class Role
{
	const ADMIN = 1;
	const TEACHER = 2;
	
	/** @Id @Column(type="integer") **/
	private $id;
	/**
	 *  @ManyToMany(targetEntity="Menu", inversedBy="roles")
	 *  @JoinTable(name="roles_menus",
	 *  	joinColumns={@JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="menu_id", referencedColumnName="id")})
	 */
	private $menus;
	/**
	 * @ManyToMany(targetEntity="Teacher", mappedBy="roles")
	 */
	private $teachers;
	
	public function __construct()
	{
		$this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->menus = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getMenus()
	{
		return $this->menus;
	}
}
<?php
namespace tfg\service\Entities;

use tfg\utils\Utils;

/**
 * @Entity @Table(name="teachers")
 **/
class Teacher extends Person
{
	/** @Column(type="string", length=40) **/
	private $email;
	/** @Column(type="string", length=13, nullable=true) **/
	private $phone;
	/** @Column(type="string", length=40) **/
	private $username;
	/** @Column(type="string", length=40) **/
	private $password;
	/**
	 * @OneToMany(targetEntity="Tutoring", mappedBy="teacher")
	 */
	private $tutorings;
	/**
	 * @OneToMany(targetEntity="Speciality", mappedBy="teacher")
	 */
	private $specialities;
	/**
	 * @OneToMany(targetEntity="Reinforcing", mappedBy="teacher")
	 */
	private $reinforcings;

	/**
	 * @ManyToMany(targetEntity="Role", inversedBy="teachers")
	 * @JoinTable(name="hasroles")
	 */
	private $roles;

	public function __construct($name, $surnames, $thumbnail = null) {
		parent::__construct($name, $surnames, $thumbnail);
		$this->email = "";
		$this->phone = "";
		$this->password = DEFAULTPASSWORD;
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
		$this->tutorings = new \Doctrine\Common\Collections\ArrayCollection();
		$this->specialities = new \Doctrine\Common\Collections\ArrayCollection();
		$this->reinforcings = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getPhone()
	{
		return $this->phone;
	}
	
	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getCompleteName():string
	{
		return $this->name . $this->surnames;
	}
	
	public function getTutorings() {
		return $this->tutorings;
	}
	
	public function getCurrentTutorings(Course $course, Trimestre $trimestre):array
	{
		$tutorings = array();
		foreach ($this->tutorings as $tutoring) {
			if ($tutoring->getCourse() == $course && $tutoring->getTrimestre() == $trimestre) {
				array_push($tutorings, $tutoring);
			}
		}
		return $tutorings;
	}
	
	public function getCurrentSpecialities(Course $course, Trimestre $trimestre):array
	{
		$specialities = array();
		foreach ($this->specialities as $speciality) {
			if ($speciality->getCourse() == $course && $speciality->getTrimestre() == $trimestre) {
				array_push($specialities, $speciality);
			}
		}
		return $specialities;
	}
	
	public function getCurrentReinforcings(Course $course, Trimestre $trimestre):array
	{
		$reinforcings = array();
		foreach ($this->reinforcings as $reinforcing) {
			if ($reinforcing->getCourse() == $course && $reinforcing->getTrimestre() == $trimestre) {
				array_push($reinforcings, $reinforcing);
			}
		}
		return $reinforcings;
	}
	
	public function isAdmin():bool
	{
		$em = Utils::getEm();
		$adminRole = $em->find('tfg\\service\\Entities\\Role', Role::ADMIN);
		return $this->roles->contains($adminRole);
	}
	
	public function setAdmin($value)
	{
		if ($this->isAdmin() != $value) {
			$em = Utils::getEm();
			$adminRole = $em->find('tfg\\service\\Entities\\Role', Role::ADMIN);
			if ($value) {
				$this->roles->add($adminRole);
			} else {
				$this->removeRole($adminRole);
			}
		}
	}
	
	public function removeRole($role)
	{
		return $this->roles->removeElement($role);
	}
	
	public function getMenuItems():array
	{
		$tmp = array();
		$menuItems = array();
		foreach ($this->roles as $role) {
			foreach ($role->getMenus() as $menu) {
				foreach ($menu->getItems() as $item) {
					array_push($tmp, $item);
				}
			}
		}
		usort($tmp, function ($a, $b) {
			return ($a->getOrder() == $b->getOrder()) ? 0 : (($a->getOrder() < $b->getOrder()) ? -1 : 1);
		});
		foreach ($tmp as $item) {
			$menuItems[$item->getName()] = $item->getFunction();
		}
		return $menuItems;
	}
}

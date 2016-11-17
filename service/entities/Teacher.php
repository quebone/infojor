<?php
namespace Infojor\Service\Entities;

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
	
	private $tutorings;
	private $specialities;
	private $reinforcings;

	/**
	 * @ManyToMany(targetEntity="Role", inversedBy="teachers")
	 * @JoinTable(name="hasroles",
	 			inverseJoinColumns={@JoinColumn(name="role_type", referencedColumnName="type")}
	 		)
	 */
	private $roles;

	public function __construct() {
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getEmail():string
	{
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getPhone():string
	{
		return $this->phone;
	}
	
	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	public function getUsername():string
	{
		return $this->username;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function getPassword():string
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
	
	public function getTutorings(\Doctrine\ORM\EntityManager $em, $course, $trimestre):array
	{
		$this->tutorings = $em->getRepository('Infojor\\Service\\Entities\\Tutoring')->findBy(array(
				'teacher'=>$this->id,
				'course'=>$course,
				'trimestre'=>$trimestre,
			));
		return $this->tutorings;
	}
	
	public function getSpecialities(\Doctrine\ORM\EntityManager $em, $course, $trimestre):array
	{
		$this->specialities = $em->getRepository('Infojor\\Service\\Entities\\Speciality')->findBy(array(
				'teacher'=>$this->id,
				'course'=>$course,
				'trimestre'=>$trimestre,
			));
		return $this->specialities;
	}
	
	public function getReinforcings(\Doctrine\ORM\EntityManager $em, $course, $trimestre):array
	{
		$this->reinforcings = $em->getRepository('Infojor\\Service\\Entities\\Reinforcing')->findBy(array(
				'teacher'=>$this->id,
				'course'=>$course,
				'trimestre'=>$trimestre,
			));
		return $this->reinforcings;
	}
	
	public function isAdmin():bool
	{
		foreach ($this->roles as $role) {
			if ($role->getType() == Role::ADMIN) {
				return true;
			}
		}
		return false;
	}
}

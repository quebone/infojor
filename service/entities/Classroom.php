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
	/**
	 * @OneToMany(targetEntity="Enrollment", mappedBy="classroom")
	 */
	private $enrollments;
	/**
	 * @OneToMany(targetEntity="Tutoring", mappedBy="classroom")
	 */
	private $tutors;
	
	public function __construct() {
		$this->tutors = new \Doctrine\Common\Collections\ArrayCollection();
		$this->enrollments = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getTutors(Course $course, Trimestre $trimestre) {
		$tutors = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($this->tutors as $tutor) {
			if ($tutor->getCourse() == $course && $tutor->getTrimestre() == $trimestre) {
				$tutors->add($tutor);
			}
		}
		return $tutors;
	}
	
	public function getScopes() {
		return $this->level->getScopes();
	}
	
	public function getEnrollments() {
		return $this->enrollments;
	}
}
	

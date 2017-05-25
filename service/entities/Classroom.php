<?php
namespace infojor\service\Entities;

use Doctrine\Common\Collections\ArrayCollection;

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
	/**
	 * @ManyToOne(targetEntity="School", inversedBy="classrooms")
	 * @JoinColumn(name="school_id", referencedColumnName="id")
	 */
	private $school;
	
	public function __construct($name, Level $level) {
		$this->name = $name;
		$this->level = $level;
		$this->tutors = new ArrayCollection();
		$this->enrollments = new ArrayCollection();
		$this->levels = new ArrayCollection();
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
	
	public function getTutors(Course $course, Trimestre $trimestre=null) {
		$tutors = new ArrayCollection();
		foreach ($this->tutors as $tutor) {
			if ($tutor->getCourse() == $course) {
				$tutors->add($tutor);
			}
		}
		return $tutors;
	}
	
	public function getStudents(Course $course, Trimestre $trimestre=null) {
		$students = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($this->enrollments as $enrollment) {
			if ($enrollment->getCourse() == $course) {
				$students->add($enrollment->getStudent());
			}
		}
		return $students;
	}
	
	public function getScopes() {
		return $this->level->getScopes();
	}
	
	public function getEnrollments() {
		return $this->enrollments;
	}
	
	public function toArray():array {
		$data = array();
		$data['id'] = $this->id;
		$data['name'] = $this->name;
		return $data;
	}
}
	

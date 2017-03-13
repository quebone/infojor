<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="school")
 **/
class School
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	private $id;
	/** @Column(type="string", length=50) **/
	private $name;
	/**
	 * @OneToOne(targetEntity="Course")
	 * @JoinColumn(name="active_course", referencedColumnName="id")
	 */
	private $activeCourse;
	/**
	 * @OneToOne(targetEntity="Trimestre")
	 * @JoinColumn(name="active_trimestre", referencedColumnName="number")
	 */
	private $activeTrimestre;
	/**
	 * @OneToMany(targetEntity="Classroom", mappedBy="school")
	 */
	private $classrooms;
	/**
	 * @OneToMany(targetEntity="ReinforceClassroom", mappedBy="school")
	 */
	private $reinforceClassrooms;
	/**
	 * @OneToMany(targetEntity="Person", mappedBy="school")
	 * @OrderBy({"surnames" = "ASC"})
	 */
	private $persons;
	
	public function __construct() {
		$this->classrooms = new \Doctrine\Common\Collections\ArrayCollection();
		$this->reinforceClassrooms = new \Doctrine\Common\Collections\ArrayCollection();
		$this->persons = new \Doctrine\Common\Collections\ArrayCollection();
		$this->logs = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}

	public function getActiveCourse()
	{
		return $this->activeCourse;
	}
	
	public function setActiveCourse(Course $course) {
		$this->activeCourse = $course;
	}

	public function getActiveTrimestre()
	{
		return $this->activeTrimestre;
	}
	
	public function setActiveTrimestre(Trimestre $activeTrimestre) {
		$this->activeTrimestre = $activeTrimestre;
	}
	
	public function getClassroomStudents(Classroom $classroom, Course $course, Trimestre $trimestre)
	{
		$students = array();
		foreach ($this->persons as $person) {
			if ($person instanceof Student) {
				foreach ($person->getEnrollments($course) as $enrollment) {
					if ($enrollment->getClassroom() == $classroom && $enrollment->getCourse() == $course && $enrollment->getTrimestre() == $trimestre) {
						array_push($students, $person);
					}
				}
			}
		}
		return $students;
	}
	
	public function getReinforceClassrooms() {
		return $this->reinforceClassrooms;
	}

	public function getClassrooms() {
		return $this->classrooms;
	}
	
	public function addStudent(Student $student) {
		$this->persons->add($student);
	}
	
	public function removePerson(Person $person) {
		return $this->persons->removeElement($person);
	}
}
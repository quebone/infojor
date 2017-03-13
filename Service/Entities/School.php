<?php
namespace Infojor\Service\Entities;

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
	 * @JoinColumn(name="active_course", referencedColumnName="year")
	 */
	private $activeCourse;
	/**
	 * @OneToOne(targetEntity="Trimestre")
	 * @JoinColumn(name="active_trimestre", referencedColumnName="number")
	 */
	private $activeTrimestre;
	/**
	 * @OneToMany(targetEntity="ReinforceClassroom", mappedBy="school")
	 */
	private $reinforceClassrooms;
	
	public function __construct() {
		$this->reinforceClassrooms = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getClassroomStudents(\Doctrine\ORM\EntityManager $em, $classroom, $course, $trimestre) {
		$enrollments = $em->getRepository('Infojor\\Service\\Entities\\Enrollment')->findBy(array(
				'course'=>$course,
				'trimestre'=>$trimestre,
			));
		$students = array();
		foreach ($enrollments as $enrollment) {
			if ($enrollment->getClassroom()->getId() == $classroom->getId()) {
				$students[] = $enrollment->getStudent();
			}
		}
		return $students;
	}
	
	public function getReinforceClassrooms() {
		return $this->reinforceClassrooms;
	}
}
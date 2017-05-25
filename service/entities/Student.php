<?php
namespace infojor\service\Entities;
use Doctrine\Common\Cache\CouchbaseCache;

/**
 * @Entity @Table(name="students")
 **/
class Student extends Person
{
	/**
	 * @OneToMany(targetEntity="GlobalEvaluation", mappedBy="student")
	 */
	private $globalEvaluations;
	/**
	 * @OneToMany(targetEntity="PartialEvaluation", mappedBy="student")
	 */
	private $partialEvaluations;
	/**
	 * @OneToMany(targetEntity="Observation", mappedBy="student")
	 */
	private $observations;
	/**
	 * @OneToMany(targetEntity="Enrollment", mappedBy="student", cascade={"remove"})
	 */
	private $enrollments;
	
	public function __construct($name, $surnames, School $school, $thumbnail = null) {
		parent::__construct($name, $surnames, $school, $thumbnail);
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->enrollments = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getEnrollments() {
		return $this->enrollments;
	}
	
	public function getEnrollment(Course $course, Trimestre $trimestre=null) {
		foreach ($this->enrollments as $enrollment) {
			if ($enrollment->getCourse() == $course)
				return $enrollment;
		}
		return null;
	}
	
	public function getClassrooms() {
		return $this->classrooms;
	}
	
	public function getClassroom(Course $course, Trimestre $trimestre=null) {
		foreach ($this->enrollments as $enrollment) {
			if ($enrollment->getCourse() == $course) {
				return $enrollment->getClassroom();
			}
		}
		return null;
	}
	
	public function getTutors(Course $course, Trimestre $trimestre) {
		$classroom = $this->getClassroom($course, $trimestre);
		return $classroom->getTutors($course, $trimestre);
	}
		
	public function getDimensionEvaluation(Dimension $dimension, Course $course, Trimestre $trimestre) {
		foreach ($this->partialEvaluations as $pe) {
			if ($pe->getCourse() == $course && $pe->getTrimestre() == $trimestre && $pe->getDimension() == $dimension) {
				return $pe;
			}
		}
		return null;
	}
	
	public function getFinalDimensionEvaluation(Dimension $dimension, Course $course) {
		foreach ($this->partialEvaluations as $pe) {
			if ($pe->getCourse() == $course && $pe->getTrimestre() == null && $pe->getDimension() == $dimension) {
				return $pe;
			}
		}
		return null;
	}
	
	public function getAreaEvaluation(Area $area, Course $course, Trimestre $trimestre) {
		foreach ($this->globalEvaluations as $ge) {
			if ($ge->getCourse() == $course && $ge->getTrimestre() == $trimestre && $ge->getArea() == $area) {
				return $ge;
			}
		}
		return null;
	}

	public function getFinalAreaEvaluation(Area $area, Course $course) {
		foreach ($this->globalEvaluations as $ge) {
			if ($ge->getCourse() == $course && $ge->getTrimestre() == null && $ge->getArea() == $area) {
				return $ge;
			}
		}
		return null;
	}
	
	public function getScopeEvaluation(Scope $scope, Course $course, Trimestre $trimestre) {
		foreach ($this->globalEvaluations as $ge) {
			if ($ge->getCourse() == $course && $ge->getTrimestre() == $trimestre && $ge->getScope() == $scope) {
				return $ge;
			}
		}
		return null;
	}
	
	public function getCourseObservation(Course $course, Trimestre $trimestre, ReinforceClassroom $reinforceClassroom = null) {
		foreach ($this->observations as $observation) {
			if ($observation->getCourse() == $course && $observation->getTrimestre() == $trimestre && $observation->getReinforceClassroom() == $reinforceClassroom) {
				return $observation;
			}
		}
		return null;
	}
	
	public function createObservation(
			$text,
			Teacher $teacher,
			Course $course,
			Trimestre $trimestre,
			ReinforceClassroom $reinforceClassroom = null):Observation {
		return new Observation($this, $teacher, $course, $trimestre, $text, $reinforceClassroom);
	}
	
	public function createDimensionEvaluation(
			Teacher $teacher,
			Dimension $dimension,
			Course $course,
			$trimestre,
			EvaluationDescription $ed):Evaluation {
		return new PartialEvaluation($this, $teacher, $course, $trimestre, $dimension, $ed);
	}

	public function createAreaEvaluation(
			Teacher $teacher,
			Area $area,
			Course $course,
			$trimestre,
			EvaluationDescription $ed):Evaluation {
		return new GlobalEvaluation($this, $teacher, $course, $trimestre, $area, $ed);
	}
	
	public function addEnrollment(Enrollment $enrollment) {
		$this->enrollments->add($enrollment);
	}
	
	public function removeEnrollment(Enrollment $enrollment) {
		return $this->enrollments->removeElement($enrollment);
	}
	
	public function getNumEvaluations(Course $course = null, Trimestre $trimestre = null) {
		if ($course == null || $trimestre == null) {
			return count($this->globalEvaluations) + count($this->partialEvaluations) + count($this->observations);
		} else {
			$num = 0;
			foreach ($this->globalEvaluations as $ge) {
				if ($ge->getCourse() == $course && $ge->getTrimestre() == $trimestre) $num++;
			}
			foreach ($this->partialEvaluations as $pe) {
				if ($pe->getCourse() == $course && $pe->getTrimestre() == $trimestre) $num++;
			}
			foreach ($this->observations as $obs) {
				if ($obs->getCourse() == $course && $obs->getTrimestre() == $trimestre) $num++;
			}
			return $num;
		}
	}
}
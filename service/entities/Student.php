<?php
namespace tfg\service\Entities;
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
	 * @OneToMany(targetEntity="Enrollment", mappedBy="student")
	 */
	private $enrollments;
	
	public function __construct($name, $surnames, $thumbnail = null) {
		parent::__construct($name, $surnames, $thumbnail);
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->enrollments = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function getEnrollments() {
		return $this->enrollments;
	}
	
	public function getEnrollment(Course $course, Trimestre $trimestre) {
		foreach ($this->enrollments as $enrollment) {
			if ($enrollment->getCourse() == $course && $enrollment->getTrimestre() == $trimestre)
				return $enrollment;
		}
		return null;
	}
	
	public function getClassrooms() {
		return $this->classrooms;
	}
	
	public function getClassroom(Course $course, Trimestre $trimestre) {
		foreach ($this->enrollments as $enrollment) {
			if ($enrollment->getCourse() == $course && $enrollment->getTrimestre() == $trimestre) {
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
	
	public function getAreaEvaluation(Area $area, Course $course, Trimestre $trimestre) {
		foreach ($this->globalEvaluations as $ge) {
			if ($ge->getCourse() == $course && $ge->getTrimestre() == $trimestre && $ge->getArea() == $area) {
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
	
	public function createObservation($text,
			Course $course,
			Trimestre $trimestre,
			ReinforceClassroom $reinforceClassroom = null):Observation {
		return new Observation($this, $course, $trimestre, $text, $reinforceClassroom);
	}
	
	public function createDimensionEvaluation(
			Dimension $dimension,
			Course $course,
			Trimestre $trimestre,
			EvaluationDescription $ed):Evaluation {
		return new PartialEvaluation($this, $course, $trimestre, $dimension, $ed);
	}

	public function createAreaEvaluation(
			Area $area,
			Course $course,
			Trimestre $trimestre,
			EvaluationDescription $ed):Evaluation {
				return new GlobalEvaluation($this, $course, $trimestre, $area, $ed);
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
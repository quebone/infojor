<?php
namespace Infojor\Service\Entities;
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
	 * @OneToMany(targetEntity="Observation", mappedBy="id")
	 */
	private $observations;
	
	public function __construct() {
		$this->globalEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->partialEvaluations = new \Doctrine\Common\Collections\ArrayCollection();
		$this->observations = new \Doctrine\Common\Collections\ArrayCollection();
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
	
	public function getCourseObservation(Course $course) {
		foreach ($this->observations as $observation) {
			if ($observation->getCourse() == $course) {
				return $observation;
			}
		}
		return null;
	}
	
	public function createObservation($text, Course $course) {
		$observation = new Observation($text);
		$observation->setStudent($this);
		return $observation;
	}
	
	public function createDimensionEvaluation(
			Dimension $dimension,
			Course $course,
			Trimestre $trimestre,
			PartialEvaluationDescription $ped):PartialEvaluation {
		return new PartialEvaluation($this, $course, $trimestre, $dimension, $ped);
	}

	public function createAreaEvaluation(
			Area $area,
			Course $course,
			Trimestre $trimestre,
			GlobalEvaluationDescription $ged):GlobalEvaluation {
				return new GlobalEvaluation($this, $course, $trimestre, $area, $ged);
	}
}
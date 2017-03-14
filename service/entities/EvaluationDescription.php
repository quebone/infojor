<?php
namespace infojor\service\Entities;

/**
 * @Entity @Table(name="evaluationdescriptions")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"global_eval" = "GlobalEvaluationDescription", "partial_eval" = "PartialEvaluationDescription"})
 */

abstract class EvaluationDescription
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	/** @Column(type="string", length=2) **/
	protected $mark;
	/** @Column(type="string", length=40) **/
	protected $description;
	/** @Column(type="integer") **/
	protected $rating;
	
	public function getId() {
		return $this->id;
	}

	public function getMark() {
		return $this->mark;
	}
	
	public function setMark($mark) {
		$this->mark = $mark;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getRating() {
		return $this->rating;
	}
	
	public function setRating($rating) {
		$this->rating = $rating;
	}
}
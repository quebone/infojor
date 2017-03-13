<?php
namespace Infojor\Service\Entities;

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
	/** @Column(type="string", length=40) **/
	protected $description;
	
	public function getId() {
		return $this->id;
	}

	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
}
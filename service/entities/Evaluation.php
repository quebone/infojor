<?php
namespace tfg\service\Entities;

/**
 * @Entity @Table(name="evaluations")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"global_eval" = "GlobalEvaluation", "partial_eval" = "PartialEvaluation"})
 */

abstract class Evaluation
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	public function getId() {
		return $this->id;
	}
}
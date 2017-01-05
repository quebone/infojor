<?php
namespace tfg\presentation\model;

interface IEvaluationViewModel
{
	public function getEvaluations($studentId, $areaId, $reinforceId, $includeSpecialities);
}
<?php
namespace tfg\presentation\controller;

use tfg\presentation\model\SchoolViewModel;
use tfg\service\SchoolService;

class DimensionsController extends Controller
{
	public function listAllDimensions()
	{
		$data = array();
		$cycleId = $_POST[CYCLE_ID];
		$degreeId = $_POST[DEGREE_ID];
		$model = new SchoolViewModel();
		$scopes = $model->listAllDimensions($cycleId);
		$result = "";
		foreach ($scopes as $scope) {
			foreach ($scope['areas'] as $area) {
				$template = new \Transphporm\Builder('../../presentation/template/dimensions.inner.xml', '../../presentation/template/dimensions.inner.tss');
				$scopes[$scope['id']]['areas'][$area['id']]['html'] = $template->output($area['dimensions'])->body;
			}
			$template = new \Transphporm\Builder('../../presentation/template/dimensions.areas.xml', '../../presentation/template/dimensions.areas.tss');
			$scopes[$scope['id']]['html'] = $template->output($scopes[$scope['id']]['areas'])->body;
		}
		$template = new \Transphporm\Builder('../../presentation/template/dimensions.scopes.xml', '../../presentation/template/dimensions.scopes.tss');
		$data = $template->output($scopes)->body;
		return $data;
	}
	
	public function getDegrees()
	{
		$model = new SchoolViewModel();
		return $model->getDegrees();
	}
	
	public function getCycles()
	{
		$degreeId = $_POST[DEGREE_ID];
		$model = new SchoolViewModel();
		return json_encode($model->getCycles($degreeId));
	}
	
	public function getAreas()
	{
		$degreeId = $_POST[DEGREE_ID];
		$model = new SchoolViewModel();
		return json_encode($model->getDegreeAreas($degreeId));
	}
	
	public function deleteDimension()
	{
		$dimId = $_POST[DIMENSION_ID];
		$model = new SchoolService();
		return $model->deleteDimension($dimId);
	}
	
	public function updateDimension()
	{
		$dimId = $_POST[DIMENSION_ID];
		$name = trim($_POST[NAME], '"');
		$description = trim($_POST[DESCRIPTION], '"');
		$cycles = json_decode(str_replace('"', '', $_POST[CYCLES]));
		$isActive = $_POST[ISACTIVE] == "true";
		$model = new SchoolService();
		return $model->updateDimension($dimId, $name, $description, $cycles, $isActive);
	}
	
	public function addDimension()
	{
		$name = trim($_POST[NAME], '"');
		$description = trim($_POST[DESCRIPTION], '"');
		$areaId = $_POST[AREA_ID];
		$cycles = json_decode(str_replace('"', '', $_POST[CYCLES]));
		$model = new SchoolService();
		return $model->addDimension($name, $description, $areaId, $cycles);
	}
}
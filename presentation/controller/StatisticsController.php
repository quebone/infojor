<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\StatisticsViewModel;
use infojor\service\SchoolService;

class StatisticsController extends Controller
{
	public function createAllSummaryTables()
	{
		// educació infantil no, no tenen ev. globals
		$classIds = array();
		$classrooms = $this->dao->getByFilter("Classroom");
		foreach ($classrooms as $classroom) {
			$degree = $classroom->getLevel()->getCycle()->getDegree();
			if ($degree->getId() == 2) {
				array_push($classIds, $classroom->getId());
			}
		}
		$model = new StatisticsViewModel();
		$at = $this->dao->getActiveTrimestre();
		$schoolService = new SchoolService();
		$trimestres = [$at->getNumber()];
		$objPHPExcel = $model->createSummaryTable($classIds, $trimestres);
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(FILESDIR . 'taula-resum.xls');
		return json_encode($classIds);
	}
}
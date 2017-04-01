<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\StatisticsViewModel;

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
		$objPHPExcel = $model->createSummaryTable($classIds, [$at->getNumber()]);
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(FILESDIR . 'taula-resum.xls');
		return json_encode($classIds);
	}

}
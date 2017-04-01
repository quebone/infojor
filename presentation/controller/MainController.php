<?php
namespace infojor\presentation\controller;

use infojor\presentation\model\UserViewModel;
use infojor\presentation\model\StatisticsViewModel;

class MainController extends Controller
{
	public function getSectionData($userId)
	{
		$userViewModel = new UserViewModel();
		return $userViewModel->getCurrentSections($userId);
	}
	
	public function createSummaryTable()
	{
		$classroomId = $_POST[CLASSROOM_ID];
		$model = new StatisticsViewModel();
		$objPHPExcel =$model->createSummaryTable(array($classroomId));
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(FILESDIR . 'taula-resum.xls');
		return json_encode($classroomId);
	}
}
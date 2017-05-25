<?php
namespace infojor\presentation\model;

use infojor\service\DAO;
use infojor\service\StatisticsService;
use infojor\service\SchoolService;

require_once BASEDIR.'vendor/fpdf/fpdf.php';
require_once BASEDIR.'vendor/PHPExcel/Classes/PHPExcel.php';

final class StatisticsViewModel extends MainViewModel {

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Crea un full excel amb la taula resum d'una classe en un trimestre i un curs donats
	 */
	public function createSummaryTable(array $classrooms, array $trimestres=null)
	{
		$schoolService = new SchoolService();
		$final = $schoolService->isLastTrimestre($this->dao->getActiveTrimestre());
		$objPHPExcel = \PHPExcel_IOFactory::load('../template/model-taula-resum.xls');
// 		$objPHPExcel = \PHPExcel_IOFactory::load('presentation/template/model-taula-resum.xls');
		// clonar el full tants cops com classes i trimestres hi hagi
		$sheet = $objPHPExcel->setActiveSheetIndex(0);
		if ($trimestres == null) {
			$trimestres = array();
			$number = $this->dao->getActiveTrimestre()->getNumber();
			for ($i = 1; $i <= $this->dao->getActiveTrimestre()->getNumber(); $i++) {
				array_push($trimestres, $i);
			}
			if ($final) array_push($trimestres, $this->dao->getActiveTrimestre()->getNumber() + 1);
		}
		for ($i = 0; $i < (count($classrooms) * count($trimestres)) - 1; $i++) {
			$clonedSheet = clone $sheet;
			$title = "full" . ($i+1);
			$clonedSheet->setTitle($title);
			$objPHPExcel->addSheet($clonedSheet);
		}
		$courseId = $this->dao->getActiveCourse()->getId();
		$model = new StatisticsService();
		for ($i = 0; $i < count($classrooms); $i++) {
			for ($j = 0; $j < count($trimestres); $j++) {
				$data = $model->getSummaryTableData($classrooms[$i], $courseId, $trimestres[$j]);
				$sheet = $objPHPExcel->setActiveSheetIndex($i+$j);
				$classroom = $this->dao->getById("Classroom", $classrooms[$i]);
				$sheet->setTitle($classroom->getName() . "-" . ($trimestres[$j] <= $this->dao->getActiveTrimestre()->getNumber() ? "t" . $trimestres[$j] : "final"));
				$nMark = 0;
				$nCol = 2;
				foreach ($data["students"] as $student) {
					$cell = $sheet->getCell("A" . $nCol);
					$cell->setValue($student->getSurnames() . ", " . $student->getName());
					$nRow = 'B';
					foreach ($data["areas"] as $area) {
						$cell = $sheet->getCell($nRow . "1");
						$cell->setValue($area->getName());
						$cell = $sheet->getCell($nRow . $nCol);
						$mark = $data["marks"][$nMark];
						if ($mark != null) {
							$cell->setValue($mark->getMark());
							$sheet->getStyle($nRow . $nCol)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
							$sheet->getStyle($nRow . $nCol)->getFill()->getStartColor()->setARGB(SUMMARY_CELL_COLORS[$mark->getMark()]);
						}
						$nMark++;
						$nRow++;
					}
					$nCol++;
				}
			}
		}
		$objPHPExcel->setActiveSheetIndex(0);
		return $objPHPExcel;
	}
}
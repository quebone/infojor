<?php

use infojor\presentation\model\ReportViewModel;
use infojor\presentation\controller\EvaluateController;
use infojor\presentation\controller\StatisticsController;
use infojor\presentation\controller\MainController;

require "init.php";

test3();

function test1() {
// 	require_once BASEDIR . 'vendor/PHPExcel/Classes/PHPExcel.php';
	$objPHPExcel = PHPExcel_IOFactory::load(TPLDIR . 'model-taula-resum.xls');
	$sheet = $objPHPExcel->setActiveSheetIndex(0);
	
	$colors = ['AE' => 'FFAAFFAA', 'AN' => 'FF669999', 'AS' => 'FFD4D46A', 'NA' => 'FFD4886A'];
	$nCols = 10;
	$firstCol = 'B';
	$lastCol = $firstCol;
	for ($i = 0; $i < $nCols; $i++) $lastCol++;
	for ($i = $firstCol; $i < $lastCol; $i++) {
		$cell = $sheet->getCell($i . '1');
		$cell->setValue("Columna " . $i);
	}
	$sheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$sheet->getStyle('B2')->getFill()->getStartColor()->setARGB($colors['AN']);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', __FILE__));
}

function test2() {
	$classrooms = [7, 15, 16, 17, 18];
	$model = new ReportViewModel();
	$objPHPExcel = $model->createSummaryTable($classrooms);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', __FILE__));
}

function test3() {
	$controller = new MainController();
	$_POST[CLASSROOM_ID] = 15;
	$controller->createSummaryTable();
// 	var_dump($controller->getTrimestres());
}
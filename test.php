<?php
require_once 'init.php';
require_once 'vendor/simi/tplengine/TplEngine.php';

function createData1() {
	$sections[0]['name'] = 'tutorings';
	$sections[0]['locale'] = 'Tutories';
	$items['id'] = 1;
	$items['name'] = 'P3A';
	$items['link'] = '/p3a.php';
	$sections[0]['items'][] = $items;
	$sections[1]['name'] = 'specialities';
	$sections[1]['locale'] = 'Especialitats';
	$items['id'] = 6;
	$items['name'] = 'Anglès';
	$items['link'] = '/angles.php';
	$sections[1]['items'][] = $items;
	$items['id'] = 2;
	$items['name'] = 'Mates';
	$items['link'] = '/mates.php';
	$sections[1]['items'][] = $items;
	return $sections;
}

function createData2() {
//definició alternativa
	$data =
		array(
			array(
				'name'=>'Tutories',
				'items'=>
					array(
						array(
							'name'=>'P3A',
							'link'=>'/p3a.php',
						),
					),
			),
			array(
				'name'=>'Especialitats',
				'items'=>
					array(
						array(
							'name'=>'Anglès',
							'link'=>'/angles.php',
						),
						array(
							'name'=>'Mates',
							'link'=>'/mates.php',
						),
					),
			),
		);
	return $data;
}

function createData3() {
	
}

function test1() {
	$inner = '';
	$sections = createData2();
	foreach ($sections as $section) {
		$inner .= '<h2>' . $section['name'] . '</h2>';
		$template = new \Transphporm\Builder(TPLDIR.'test.xml', TPLDIR.'test.tss');
		$inner .= $template->output($section)->body;
	}
	echo($inner);
}

function test2() {
	$data['sections'] = createData1();
	var_dump($data);
	$xml = '<div class="section"><h2>Section</h2>
			<div class="item">Item</div>
			';
	$tss = '.section {repeat:data(sections)}
			.section h2 {content:iteration(locale)}
			.section:attr(id) {content:iteration(name)}
			.section .item {repeat:data(sections.items)}
			';
	$template = new \Transphporm\Builder($xml, $tss);
	echo $template->output($data)->body;
}

function test3() {
	$tplEngine = new \Simi\TplEngine\TplEngine();
	$data = new \stdClass();
	$data->sections = createData1();
	var_dump($data);
	$xml = '<main></main>
			';
	$tag = '<main>';
	echo $tplEngine->output('createSections', $data, $xml, $tag);
}

function test4($em) {
	$viewModel = new \Infojor\Presentation\Model\SchoolViewModel(null, $em);
	$evaluationViewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $em);
	$partialEvaluationDescriptions = $evaluationViewModel->getPartialEvaluationDescriptions();
	$globalEvaluationDescriptions = $evaluationViewModel->getGlobalEvaluationDescriptions();
	$classroomId = 10;
	$studentId = 15;
	$classroom = $viewModel->getClassroom($classroomId);
	echo '<div id="seccio"><div id="left" class="selector"></div><h2>' .
		$classroom->classroom . '</h2><div id="right" class="selector"></div></div>' . "\n";
	$scopes = $viewModel->getClassroomScopes($classroomId);
	echo '<ul>' . "\n";
	foreach ($scopes->scopes as $scope) {
		echo "\t<li>" . $scope->name . "\n";
		$areas = $viewModel->getScopeAreas($scope->id);
		echo "\t<ul>\n";
		foreach ($areas->areas as $area) {
			echo "\t\t<li>" . $area->name;
			$dimensions = $viewModel->getAreaDimensions($area->id);
			echo "\n\t\t<ul>\n";
			foreach ($dimensions->dimensions as $dimension) {
				$checked = false;
				$pe = $evaluationViewModel->getDimensionEvaluation($studentId, $dimension->id);
				echo "\t\t\t<li>" . $dimension->name . " <em>(" . $dimension->description . ")</em>";
				foreach ($partialEvaluationDescriptions->peds as $ped) {
					if (!strcmp($ped->mark,  $pe)) $checked = true; 
					echo "<input type='radio' name='dim" . $dimension->id . "' value='" . $ped->mark . "'" . 
						(!strcmp($ped->mark,  $pe) ? " checked" : "") . "/>" . $ped->mark . "\n";
				}
				echo "<input type='radio' name='dim" . $dimension->id . "' value='NV' class='not_evaluated'" .
						($checked ? "" : " checked") . "/>NV\n";
				echo "</li>\n";
			}
			echo "\t\t</ul>\n";
			echo "\t\t<p>Qualificació Global";
			$checked = false;
			$ge = $evaluationViewModel->getAreaEvaluation($studentId, $area->id);
			foreach ($globalEvaluationDescriptions->geds as $ged) {
				if (!strcmp($ged->mark,  $ge)) $checked = true;
				echo "<input type='radio' name='area" . $area->id . "' value='" . $ged->mark . "'" .
						(!strcmp($ged->mark,  $ge) ? " checked" : "") . "/>" . $ged->mark . "\n";
			}
			echo "<input type='radio' name='area" . $area->id . "' value='NV' class='not_evaluated'" .
				($checked ? "" : " checked") . " />NV\n";
			echo "</p>\n";
			echo "\t\t</li>\n";
		}
		echo "\t</ul>\n";
		echo "\t</li>\n";
	}
	echo "</ul>\n";
}

function test5($em) {
	$viewModel = new \Infojor\Presentation\Model\EvaluationViewModel(null, $em);
	echo $viewModel->setGlobalEvaluation(13, 11, 4);
	echo "\n";
}

function test6($em) {
	$schoolModel = new \Infojor\Service\SchoolService($em);
	$classroom = $schoolModel->getClassroom(7);
	$level = $classroom->getLevel();
	$cycle = $level->getCycle();
	$area = $schoolModel->getArea(10);
	$dimensions = $area->getDimensions();
	foreach ($dimensions as $dimension) {
		$cycles = $dimension->getCycles();
		foreach ($cycles as $dimensionCycle) {
			$id = $dimensionCycle->getId();
		}
	}
	echo "ok\n";
}

function test7($em) {
	$userModel = new \Infojor\Service\UserService($em);
	$schoolModel = new \Infojor\Service\SchoolService($em);
	$course = $schoolModel->getActiveCourse();
	$student = $userModel->getStudent(15);
	$observation = $student->getCourseObservation($course);
}

function test8() {
	$html = "<input type='textarea' onchange='changed(this)' \>
			<script>function changed(elem){console.log(JSON.stringify(elem.value))}</script>";
	echo $html;
}

test8();
<?php
/**
 * Custom template engine for tfg
 */
namespace infojor\presentation\view;

use infojor\service\SchoolService;
use infojor\service\DAO;

class TplEngine
{
	public function output($function, $data, $xml = null, $tag = null)
	{
		$contents = $this->{$function}($data);
		if ($xml != null && $tag != null ) {
			$pos = strpos($xml, $tag) + strlen($tag);
			$contents = substr_replace($xml, $contents, $pos, 0);
		}
		return $contents;
	}
	
	public function arrayToSelect($options)
	{
		$html = "<select>";
		foreach ($options as $option) {
			$html .= "<option value='" . reset($option) . "'>" . next($option) . "</option>";
		}
		$html .= "</select>";
		return $html;
	}
	
	public function arrayToCheckbox($data, $actives, $attr)
	{
		$html = "";
		foreach ($data as $checkbox) {
			$checked = false;
			foreach ($checkbox as $key=>$value) {
				foreach ($actives as $active) {
					if ($value == $active[$key]) $checked = true;
				}
			}
			$html .= "<input type='checkbox' value='";
			$html .= reset($checkbox) . "' title='" . next($checkbox);
			$html .= "'" . ($checked ? " checked" : "") . " " . $attr . ">";
		}
		return $html;
	}
	
	private function createSections($data)
	{
		$inner = '';
		foreach ($data as $section) {
			$inner .= '<div class="section">' .
				'<p class="section_name">' . $section['locale'] . '</p>' . "\n";
			$inner .= '<ul>' . "\n";
			foreach ($section['items'] as $item) {
				$inner .= "<li>";
				$inner .= '<a onclick="load(' . "'" . $section['name'] . '-' . $item->id . "'" . ')">' .
						$item->name . '</a>';
				if (isset($section['excel']))
					$inner .= ' <img src="' . VIEWDIR . '/images/excel.png" onclick="createSummaryTable(' . $item->id . ')" />';
				$inner .= '</li>';
			}
			$inner .= "</ul>\n</div>";
		}
		return $inner;
	}
	
	private function createEvaluations($data)
	{
		$schoolService = new SchoolService();
		$dao = new DAO();
		$at = $data['trimestre'];
		$degreeId = $dao->getById("Classroom", $data['classroom']['id'])->getLevel()->getCycle()->getDegree()->getId();
		$final = $schoolService->isLastTrimestre($dao->getActiveTrimestre()) && $degreeId == 2;
		$inner = "<div id='evaluations'><ul>\n";
		foreach ($data['scopes'] as $scope) {
			$inner .= "\t<li class='scope' title=\"" . $scope['description'] . "\"><h3>" . $scope['name'] . "</h3>\n";
			$inner .= "\t<ul>\n";
			foreach ($scope['areas'] as $area) {
				$inner .= "\t\t<li class='area'><h4>" . $area['name'] . "</h4>";
				$inner .= "\n\t\t<table>\n";
				if (count($data['previousTrimestres']) > 0) {
					$inner .= "<tr><th></th><th></th>";
					if ($final) {
						$inner .= "<th>Finals</th>";
					}
					foreach ($data['previousTrimestres'] as $trimestre) {
						$inner .= "<th>t" . $trimestre['number'] . "</th>";
					}
					$inner .= "</tr>";
				}
				foreach ($area['dimensions'] as $dimension) {
					$inner .= "\t\t\t<tr><td class='dimension' title=\"" . $dimension['description'] . "\">" . $dimension['name'] . "</td>";
					$inner .= $this->createDimensionRadio($data, $dimension, $at);
					$inner .= $this->createDimensionSelect($data, $dimension, $at);
					if ($final) {
						$inner .= $this->createDimensionSelect($data, $dimension, $at+1, $final);
					}
					for ($i = 1; $i < $at; $i++)
						$inner .= "<td class='mark'>" . $dimension['pes'][$i]['mark'] . "</td>";
					$inner .= "<td class='definition'>" . $dimension['description'] . "</td>";
					$inner .= "</tr>\n";
				}
				$inner .= "\t\t</table>\n";
				$inner .= "\t\t<div class='global_eval'>Qualificació Global";
				$inner .= $this->createAreaRadio($data, $area, $at);
				$inner .= $this->createAreaSelect($data, $area, $at);
				if ($final) {
					$inner .= $this->createAreaSelect($data, $area, $at+1, $final);
				}
				for ($i = 1; $i < $at; $i++)
					$inner .= "<span class='mark'>" . $area['ges'][$i]['mark'] . "</span>";
				$inner .= "</div>\n";
				$inner .= "\t\t</li>\n";
			}
			$inner .= "\t</ul>\n";
			$inner .= "\t</li>\n";
		}
		if ($data['observation'] !== null) {
			$inner .= "\t<li class='scope'><h3>Observacions</h3>\n";
			$inner .= "<textarea class='observation' name='" . $data['reinforcing']['id'] . 
				"'onchange='changeObservation(this)'>" . $data['observation'] . "</textarea>\n";
		}
		if ($data['reinforce'] !== null) {
			$inner .= "\t<li class='scope'><h3>" . $data['reinforce']['name'] . "</h3>\n";
			$inner .= "<textarea class='observation' name='" . $data['reinforce']['id'] . 
				"' onchange='changeObservation(this)'>" . $data['reinforce']['observation']['text'] . "</textarea>\n";
		}
		$inner .= "</ul></div>\n";
		return $inner;
	}
	
	private function createDimensionRadio($data, $dimension, $at)
	{
		$checked = false;
		$inner = "<td class='input'>";
		$pe = $dimension['pes'][$at]['mark'];
		foreach ($data['peds'] as $ped) {
			if (!strcmp($ped['mark'], $pe)) $checked = true;
			$inner .= "<input type='radio' name='dim" . $dimension['id'] . "' value='" . $ped['id'] .
			"' title='" . $ped['description'] . "' onchange='changePE(this)'" . (!strcmp($ped['mark'], $pe) ? " checked" : "") . " />" . $ped['mark'] . "\n";
		}
		$inner .= "<input type='radio' name='dim" . $dimension['id'] .
		"' value='0' title='No valorat' onchange='changePE(this)' class='not_evaluated'" .
		($checked ? "" : " checked") . " /><span class='not_evaluated'>NV</span>\n";
		$inner .= "</td>";
		return $inner;
	}
	
	private function createDimensionSelect($data, $dimension, $at, $final=false)
	{
		$checked = false;
		$baseName = $final ? "final" : "dim";
		$class = $final ? "final" : "trimestre";
		$inner = "<td class='" . $class . "'><select name='" . $baseName . $dimension['id'] . "' onchange='changePE(this)'>";
		$pe = $dimension['pes'][$at]['mark'];
		foreach ($data['peds'] as $ped) {
			if (!strcmp($ped['mark'], $pe)) $checked = true;
			$inner .= "<option value='" . $ped['id'] .
			"'" . (!strcmp($ped['mark'], $pe) ? " selected" : "") . ">" . $ped['mark'] . "</option>\n";
		}
		$inner .= "<option value='0'" . ($checked ? "" : " selected") . ">NV</option>\n";
		$inner .= "</select></td>";
		return $inner;
	}
	
	private function createAreaRadio($data, $area, $at)
	{
		$inner = "<span class = 'input'>";
		$checked = false;
		$ge = $area['ges'][$at]['mark'];
		foreach ($data['geds'] as $ged) {
			if (!strcmp($ged['mark'], $ge)) $checked = true;
			$inner .= "<input type='radio' name='area" . $area['id'] . "' value='" . $ged['id'] . "'" .
					" title='" . $ged['description'] . "' onchange='changeGE(this)'" . (!strcmp($ged['mark'], $ge) ? " checked" : "") . " />" . $ged['mark'] . "\n";
		}
		$inner .= "<input type='radio' name='area" . $area['id'] . "' value='0' class='not_evaluated'" .
				" title='No valorat' onchange='changeGE(this)'" .
				($checked ? "" : " checked") . " /><span class='not_evaluated'>NV</span>\n";
		$inner .= "</span>";
		return $inner;
	}

	private function createAreaSelect($data, $area, $at, $final=false)
	{
		$baseName = $final ? "final" : "area";
		$class = $final ? "final" : "trimestre";
		$inner = "<select class='" . $class . "' name='" . $baseName . $area['id'] . "' onchange='changeGE(this)'>";
		$checked = false;
		$ge = $area['ges'][$at]['mark'];
		foreach ($data['geds'] as $ged) {
			if (!strcmp($ged['mark'], $ge)) $checked = true;
			$inner .= "<option value='" . $ged['id'] . "'" .
					(!strcmp($ged['mark'], $ge) ? " selected" : "") . ">" . $ged['mark'] . "</option>\n";
		}
		$inner .= "<option value='0'" . ($checked ? "" : " selected") . ">NV</option>\n";
		$inner .= "</select>";
		return $inner;
	}
}

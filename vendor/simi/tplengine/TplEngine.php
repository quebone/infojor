<?php
/**
 * Custom template engine for Infojor
 */
namespace Simi\TplEngine;

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
	
	private function createSections($data)
	{
		$inner = '';
		foreach ($data as $section) {
			$inner .= '<div class="section">' .
				'<p class="section_name">' . $section['locale'] . '</p>' . "\n";
			$inner .= '<ul>' . "\n";
			foreach ($section['items'] as $item) {
				$inner .= '<li><a onclick="load(' . "'" . $section['name'] . '-' . $item->id . "'" . ')">' .
						$item->name . '</a></li>';
			}
			$inner .= "</ul>\n</div>";
		}
		return $inner;
	}
	
	private function createEvaluations($data)
	{
// 		$inner = "<input id='classroomId' type='hidden' value='" . $data['classroom']['id'] . "' />\n";
		$inner = "<div id='section'><div id='section_header'><div class='selector' name='left' onclick='prevClassroom()'></div><h2>" .
			$data['classroom']['name'] . "</h2><div class='selector' name='right' onclick='nextClassroom()'></div></div>
					\n\t<h3> " . $data['student']['name'] . "\t\n</div>\n";
		$inner .= "<ul>\n";
		foreach ($data['scopes'] as $scope) {
			$inner .= "\t<li class='scope'><h3>" . $scope['name'] . "</h3>\n";
			$inner .= "\t<ul>\n";
			foreach ($scope['areas'] as $area) {
				$inner .= "\t\t<li class='area'><h4>" . $area['name'] . "</h4>";
				$inner .= "\n\t\t<table>\n";
				foreach ($area['dimensions'] as $dimension) {
					$checked = false;
					$inner .= "\t\t\t<tr><td class='dimension' descr=\"" . $dimension['description'] . "\">" . $dimension['name'] . "</td><td class='input'>";
					$pe = $dimension['pe'];
					foreach ($data['peds'] as $ped) {
						if (!strcmp($ped['mark'], $pe)) $checked = true;
						$inner .= "<input type='radio' name='dim" . $dimension['id'] . "' value='" . $ped['id'] . 
							"' onchange='changePE(this)'" . (!strcmp($ped['mark'], $pe) ? " checked" : "") . " />" . $ped['mark'] . "\n";
					}
					$inner .= "<input type='radio' name='dim" . $dimension['id'] . 
						"' value='0' onchange='changePE(this)' class='not_evaluated'" . 
						($checked ? "" : " checked") . " />NV\n";
					$inner .= "</td></tr>\n";
				}
				$inner .= "\t\t</table>\n";
				$inner .= "\t\t<div class='global_eval'>Qualificació Global<span class = 'input'>";
				$checked = false;
				$ge = $area['ge'];
				foreach ($data['geds'] as $ged) {
					if (!strcmp($ged['mark'], $ge)) $checked = true;
					$inner .= "<input type='radio' name='area" . $area['id'] . "' value='" . $ged['id'] . "'" .
						" onchange='changeGE(this)'" . (!strcmp($ged['mark'], $ge) ? " checked" : "") . " />" . $ged['mark'] . "\n";
				}
				$inner .= "<input type='radio' name='area" . $area['id'] . "' value='0' class='not_evaluated'" .
					" onchange='changeGE(this)'" . ($checked ? "" : " checked") . " />NV\n";
				$inner .= "</span></div>\n";
				$inner .= "\t\t</li>\n";
			}
			$inner .= "\t</ul>\n";
			$inner .= "\t</li>\n";
		}
		if ($data['observation'] != null) {
			$inner .= "\t<li class='scope'><h3>Observacions</h3>\n";
			$inner .= "<input type='textarea' class='observation' onchange='changeObservation(this)' />" . $data['observation'] . "\n";
		}
		$inner .= "</ul>\n";
		return $inner;
	}
}

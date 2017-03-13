<?php
/**
 * Custom template engine for tfg
 */
namespace tfg\presentation\view;

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
				$inner .= '<li><a onclick="load(' . "'" . $section['name'] . '-' . $item->id . "'" . ')">' .
						$item->name . '</a></li>';
			}
			$inner .= "</ul>\n</div>";
		}
		return $inner;
	}
	
	private function createEvaluations($data)
	{
		$inner = "<div id='evaluations'><ul>\n";
		foreach ($data['scopes'] as $scope) {
			$inner .= "\t<li class='scope' title=\"" . $scope['description'] . "\"><h3>" . $scope['name'] . "</h3>\n";
			$inner .= "\t<ul>\n";
			foreach ($scope['areas'] as $area) {
				$inner .= "\t\t<li class='area'><h4>" . $area['name'] . "</h4>";
				$inner .= "\n\t\t<table>\n";
				foreach ($area['dimensions'] as $dimension) {
					$checked = false;
					$inner .= "\t\t\t<tr><td class='dimension' title=\"" . $dimension['description'] . "\">" . $dimension['name'] . "</td><td class='input'>";
					$pe = $dimension['mark'];
					foreach ($data['peds'] as $ped) {
						if (!strcmp($ped['mark'], $pe)) $checked = true;
						$inner .= "<input type='radio' name='dim" . $dimension['id'] . "' value='" . $ped['id'] . 
							"' title='" . $ped['description'] . "' onchange='changePE(this)'" . (!strcmp($ped['mark'], $pe) ? " checked" : "") . " />" . $ped['mark'] . "\n";
					}
					$inner .= "<input type='radio' name='dim" . $dimension['id'] . 
						"' value='0' title='No valorat' onchange='changePE(this)' class='not_evaluated'" . 
						($checked ? "" : " checked") . " />NV\n";
					$inner .= "</td></tr>\n";
				}
				$inner .= "\t\t</table>\n";
				$inner .= "\t\t<div class='global_eval'>Qualificació Global<span class = 'input'>";
				$checked = false;
				$ge = $area['mark'];
				foreach ($data['geds'] as $ged) {
					if (!strcmp($ged['mark'], $ge)) $checked = true;
					$inner .= "<input type='radio' name='area" . $area['id'] . "' value='" . $ged['id'] . "'" .
						" title='" . $ged['description'] . "' onchange='changeGE(this)'" . (!strcmp($ged['mark'], $ge) ? " checked" : "") . " />" . $ged['mark'] . "\n";
				}
				$inner .= "<input type='radio' name='area" . $area['id'] . "' value='0' class='not_evaluated'" .
					" title='No valorat' onchange='changeGE(this)'" . ($checked ? "" : " checked") . " />NV\n";
				$inner .= "</span></div>\n";
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
}

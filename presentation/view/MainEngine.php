<?php
namespace infojor\presentation\view;

class MainEngine
{
	public function outputSection($data, $sectionName)
	{
		$output = "";
		foreach ($data as $section) {
			if (!strcmp($section['name'], $sectionName)) {
				foreach ($section['items'] as $item) {
					$output .= "<li><span class='link' title='editar informes'>
							<a onclick=\"load('" . $section['name'] . "-" . $item['id']. "')\">" . $item['name'] . "</a></span>";
					if (isset($section['excel']))
						$output .= " <i class='material-icons md-24 link' title='taula resum' 
								onclick='createSummaryTable(" . $item['id'] . ")'>description</i>";
					}
					$output .= "</li>\n";
			}
		}
		return $output;
	}
}
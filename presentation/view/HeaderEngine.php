<?php
namespace infojor\presentation\view;

class HeaderEngine
{
	public function header($data)
	{
		$output = "";
		$isActive = true;
		foreach ($data['menus'] as $menu) {
			if (!$menu['isDropdown']) {
				foreach ($menu['items'] as $item) {
					$output .= "<li class='link";
					if ($isActive) {
						$output .= " active";
						$isActive = false;
					}
					$output .= "' onclick='" . $item['function'] . "()'><a>" . $item['name'] . "</a></li>\n";
				}
			} else {
				$output .= "<li class='dropdown'>\n";
				$output .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>" .
					(!strcmp($menu['name'], "teacher") ? $data['user'] : $menu['name']) . "<span class='caret'></span></a>\n";
				$output .= "<ul class='dropdown-menu'>\n";
				foreach ($menu['items'] as $item) {
					$output .= "<li class='link' onclick='" . $item['function'] . "()'><a>" . $item['name'] . "</a></li>\n";
				}
				$output .= "</ul>\n</li>\n";
			}
		}
		return $output;
	}
	
	public function footer($data)
	{
		$output = $data['school'];
		return $output;
	}
}
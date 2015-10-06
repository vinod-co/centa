<?php
// This file is part of Rogō
//
// Rogō is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogō is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogō.  If not, see <http://www.gnu.org/licenses/>.

/**
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

echo $configObject->get('cfg_js_root');

function draw_toprightmenu($helpID = 1) {
  global $string;
	
  $configObject = Config::get_instance();
  $userObject		= UserObject::get_instance();

  $html = '';
	
  if ($userObject->is_impersonated()) {
		$impersonated_name = $userObject->get_title() . ' ' . $userObject->get_initials() . ' ' . $userObject->get_surname();
		$html .= '<div id="toprightmenu" style="width:250px">';
		$html .= '<div class="impersonate"><img src="' . $configObject->get('cfg_root_path') . '/artwork/agent.png" width="32" height="32" alt="Impersonate" style="float:left; padding-right:6px"/><div style="padding-top:1px; padding-right:1.4em">' . $string['loggedinas'] . ':<br /><strong>' . $impersonated_name . '</strong></div></div>';
	} else {
		$html .= '<div id="toprightmenu">';
	}

	if ($userObject->has_role('SysAdmin')) {
    $html .= '<div class="trm_div" id="admintools">' . $string['administrativetools'] . '</div>';
  }
  $html .= "<div class=\"trm_div\" onclick=\"toprightmenu_launchHelp($helpID)\">" . $string['helpsupport'] . "</div>";
  $html .= '<div class="trm_div" id="signout" style="background-image: url(\'' . $configObject->get('cfg_root_path') . '/artwork/logout16.gif\'); background-repeat: no-repeat; background-position: 0 3px">' . $string['signout'] . '</div>';
  $html .= '<div class="trm_div" id="aboutrogo">' . sprintf($string['aboutrogo'], $configObject->get('rogo_version')) . '</div>';

  $html .= '</div>';
	
  return $html;
}
?>
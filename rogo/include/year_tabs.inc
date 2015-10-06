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

function drawTabs($current_tab, $type, $start_offset, $end_offset, $extra = '') {
  $html = '<table cellpadding="0" cellspacing="0" border="0" style="margin-top:8px; margin-left:auto; margin-right:0px"><tr>';
  
  $start_year = (intval(date("Y")) - $start_offset);
	
  for ($tmp_year=$start_year; $tmp_year < $start_year + $start_offset + $end_offset; $tmp_year++) {
	  if ($type == 'academic') {
		  $inc_year = (substr($tmp_year,-2) + 1);
			if ($inc_year < 10) $inc_year = '0' . $inc_year;
		
		  $tab_year = $tmp_year . '/' . $inc_year;
		} else {
		  $tab_year = $tmp_year;
		}
		
    if ($tmp_year == $current_tab) {
      $html .= "<td class=\"tabon\" onclick=\"location.href='" . $_SERVER['PHP_SELF'] . "?calyear=" . $tmp_year . $extra . "'\">" . $tab_year . "</td>";
    } else {
      $html .= "<td class=\"taboff\" onclick=\"location.href='" . $_SERVER['PHP_SELF'] . "?calyear=" . $tmp_year . $extra ."'\">" . $tab_year . "</td>";
    }
  }
  $html .= "</tr></table>";
  
  return $html;
}
?>
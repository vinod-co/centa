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
* 
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

$menuNo = 0;

function makeMenu($options) {
  global $menuNo, $configObject;
  
  // Work out the width of the menu
  $longest_string = 0;
  foreach ($options as $title=>$url) {
    if (strlen($title) > $longest_string) $longest_string = strlen($title);    
  }
  $width = ($longest_string * 7) + 35;
  if ($width < 60) $width = 60;  // Set a reasonable minumum width
  
  echo "<script>\n";
  echo "var myOptions" . $menuNo . "=new Array(";
  $line = 0;
  foreach ($options as $title=>$url) {
    $tmp_parts = explode('[BR]', $title);
    if (count($tmp_parts) > 1) {
      $title = $tmp_parts[1];
    } else {
      $title = $tmp_parts[0];
    }
  
    if ($line == 0) {
      echo '"' . $title .'"';
    } else {
      echo ',"' . $title .'"';
    }
    $line++;
  
  }
  echo ");\n";
  echo "var myURLs" . $menuNo . "=new Array(";
  $line = 0;
  foreach ($options as $title=>$url) {
    if ($line == 0) {
      echo '"' . $url .'"';
    } else {
      echo ',"' . $url .'"';
    }
    $line++;
  
  }
  echo ");\n";
  echo "</script>\n";
  
  $line = 0;
  echo "<div id=\"popup$menuNo\" class=\"popup\" style=\"width:" . $width . "px; z-index:30\">\n";
  
  if (count($options) > 20) echo "<div id=\"" . $menuNo . "_up\" class=\"popupitem\" onmouseover=\"scrollUpStart('popup$menuNo',myOptions" . $menuNo . ",myURLs" . $menuNo . ",'myOptions" . $menuNo . "')\" onMouseOut=\"scrollUpEnd()\"><img src=\"{$configObject->get('cfg_root_path')}/artwork/submenu_up_off.png\" width=\"9\" height=\"5\" alt=\"down\" border=\"0\" />&nbsp;</div>\n";
  foreach ($options as $title=>$url) {
    $itemID = $menuNo . '_' . $line;
    if (substr($url,0,1) == '-') {
      echo "<div class=\"popupitemline\" id=\"$itemID\" onclick=\"hideMenus(event)\">1<hr noshade=\"noshade\" style=\"height:1px; border:none; background-color:#C0C0C0; color:#C0C0C0\" /></div>\n";
      } elseif (substr($url,0,1) == '#') {
      echo "<div class=\"popupitembold\" id=\"$itemID\">" . substr($url,1) . "</div>\n";
    } else {
      if (stripos($url,'JavaScript:') === false) {
        echo "<div class=\"popupitem\" id=\"$itemID\" onclick=\"window.location='$url'\">/$title</div>\n";
      } else {
        echo "<div class=\"popupitem\" id=\"$itemID\" onclick=\"$url\">/$title</div>\n";
      }
    }
    $line++;
    if ($line > 19) break;
  }
  if (count($options) > 20) echo "<div id=\"" . $menuNo . "_down\" class=\"popupitem\" onmouseover=\"scrollDownStart('popup$menuNo',myOptions" . $menuNo . ",myURLs" . $menuNo . ",'myOptions" . $menuNo . "')\" onMouseOut=\"scrollDownEnd()\"><img src=\"{$configObject->get('cfg_root_path')}/artwork/submenu_down_on.png\" width=\"9\" height=\"5\" alt=\"down\" border=\"0\" />&nbsp;</div>\n";
  
  echo "</div>\n";
  $menuNo++;
}

function hideMenuScript($menu_no) {
  echo "<script>\n";
  echo "function hideMenus(evt) {\n";
  for ($i=0; $i<$menu_no; $i++) {
    echo "$('#popup$i').hide();\n";
  }
  echo "}\n";
  echo "</script>\n";
}
?>
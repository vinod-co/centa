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
* Script reads all the language files in /lang folder and compares strings in search for strings that are empty, duplicate, identical etc. 
* 
* @author Nikodem Miranowicz
* @version 1 . 0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/sysadmin_auth.inc';
?> 
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  
  <title>lang test</title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <style type="text/css">
    body {
      font-size: 90%;
      padding-left: 20px;
    }
    table {
      padding-left: 20px;
    }
    #trans {
			position: absolute;
      padding: 6px 16px 6px 0px;
      color: black;
      background-color: white;
      border: 1px solid #767676;
      max-width: 400px;
      font-size: 80%;
      text-align: justify;
      z-index: 100;
      background: -moz-linear-gradient(top, #FFFFFF, #E4E5F0);
      background: -webkit-linear-gradient(top, #FFFFFF, #E4E5F0);
      background-image: -ms-linear-gradient(top, #FFFFFF 0%, #E4E5F0 100%);
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#E4E5F0');
      -moz-box-shadow:    3px 3px 5px 6px #ccc;
      -webkit-box-shadow: 3px 3px 5px 6px #ccc;
      box-shadow:         2px 2px 4px 0px #808080;
      border-radius: 3px;
      display: none;
    }
    .plusminus {
      width: 11px;
      height: 11px;
    }
  </style>
  
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery-ui-1.10.4.min.js"></script>
  <script>
    $(function () {
      $(document).tooltip();
    })
		
		function show_transl(e, txt) {
			//console.log(txt);
      
      if (!e) var e = window.event;
      var currentX = e.clientX;
      var currentY = e.clientY;
      var scrOfX = $(document).scrollLeft();
      var scrOfY = $(document).scrollTop();      
      
			$('#trans').show();
			$('#trans').html(txt);
      $('#trans').css('top', currentY + scrOfY + "px");
      $('#trans').css('left', (currentX + scrOfX + 20)  + "px");
      
			if (txt == '') {
        $('#trans').hide();
      }
		}
    
		function showhide(id) {
			if ($('#' + id).is(':visible')) {
				$('#' + id).hide();
				$('#bh_'+id).hide();
				$('#bs_'+id).show();
			} else {
				$('#' + id).show();
				$('#bh_'+id).show();
				$('#bs_'+id).hide();
			}
		}
  </script>  
</head>

<body>
<?php

//function for recursive files search
function file_array($path, $exclude) {
  global $files;

  $path = rtrim($path, "/") . "/";
  $result = array();
  $folder_handle = opendir($path);
  while (false !== ($filename = readdir($folder_handle))) {
    if (!in_array(strtolower($filename), $exclude)) {
      if (is_dir($path . $filename . "/")) {
        $result[] = file_array($path . $filename . "/", $exclude);
      } else {
        array_push($files, $path . $filename);
      }
    }
  }
  return $result;
}
$required = array();

//function for reading string into the array
function file_array_read($files, $lang) {
  global $required;
  
	$strings = array(array());
	foreach($files as $filepath) {
    $filepath_parts = preg_split('/\/en\//', $filepath);
    $filepath = $filepath_parts[0] . '/' . $lang . '/' . $filepath_parts[1];
    if (file_exists($filepath)) {
      $file_contents = file_get_contents($filepath);
      $file_contents = preg_replace('/</', '&lt;', $file_contents);
      //split the lines
      $file_lines = preg_split('/\n/', $file_contents);
      foreach ($file_lines as $file_line) {
        $file_line = trim ($file_line);
        //split the string array element line
				if (substr($file_line, 0, 7)=='require') {
					$target = trim(substr($file_line, 7));
          $target = preg_replace('/ /', '', $target);
					$target = preg_replace('/\'/', '', $target);
					$target = preg_replace('/\"/', '', $target);
					$target = preg_replace('/_once/', '', $target);
          $target = preg_replace('/lang\//', '', $target);					
          $target = preg_replace('/\$cfg_web_root/', '', $target);
          $target = preg_replace('/\$configObject\-\>get\(cfg_web_root\)/', '', $target);
          $target = preg_replace('/\$language/', '', $target);
					$target = preg_replace('/\.\.\//', '', $target);
					$target = preg_replace('/\.\//', '', $target);
					$target = preg_replace('/^\./', '', $target);
					$target = preg_replace('/;/', '', $target);
          if (!isset($required[$filepath_parts[1].'|'.$target])) {
            $required[$filepath_parts[1].'|'.$target] = 0;
          } 
          $required[$filepath_parts[1].'|'.$target]++;
				}
				if (substr($file_line, 0, 7)=='$string') {
          $file_line_parts = preg_split('/=/', $file_line);
          $line_string = $file_line_parts[0];
          $line_text = trim(substr($file_line, strlen($line_string)));
          //remove comments except for '//cognate'
          if ((strpos($line_text, '//') !== false) and (strpos($line_text, '//cognate') === false)) {
            $line_text = trim(substr($line_text, 0, strpos($line_text, '//')));
          }
          $line_text = preg_replace('/^[\s=\s\"\']+/', '', $line_text);
          $line_text = preg_replace('/[\'\";\s]+$/', '', $line_text);

          $line_string = preg_replace('/^[\s]+/', '', $line_string);
          $line_string = preg_replace('/[\s]+$/', '', $line_string);
          $line_string = substr($line_string, 7, -1);
          $line_string = preg_replace('/^[\[][\']/', '', $line_string);
          $line_string = preg_replace('/[\]]$/', '', $line_string);
          $line_string = preg_replace('/[\']$/', '', $line_string);
          
          if (!isset($strings[$line_string])) {
            $strings[$line_string] = array($filepath_parts[1], $line_string, $line_text, 1);
          } else {
            $strings[$line_string][0] .= '|' . $filepath_parts[1];
            $strings[$line_string][2] .= '|' . $line_text;
            $strings[$line_string][3] += 1;
          }
        }
      }	
    } else {
      $strings[$filepath_parts[1]] = array($filepath_parts[1], '', '', 1);
    }
  }
	return $strings;
}

$last_key = '';
$last_value = '';
$trans_list = Array();
 
function display_this($data, $data_index) {
	global $display_text;
  global $last_key,$last_value;
	global $strings_pl;
	global $global_for_translated_list;
  
  $data_part1 = explode('|', $data[0]);
	$data_part2 = $data[1];
	$data_part3 = explode('|', $data[2]);
	$transl = '';
	if ($global_for_translated_list) {
		unset($trans_list);
		$transl = '&nbsp;<a href="#" onclick="show_transl(event,\'\');return false;" onmouseover="show_transl(event,\'<ul>';
		foreach ($data_part3 as $dp) $trans_list[$dp] = $dp;
		foreach ($trans_list as $tl) {
			$tl = preg_replace('/\"/','\"',$tl);
			$tl = preg_replace('/\'/',"\"",$tl);
			$tl = preg_replace('/\'; \/\/cognate/','',$tl);
			$tl = preg_replace('/\"; \/\/cognate/','',$tl);
			$tl = htmlentities($tl,ENT_QUOTES,'UTF-8');
			$transl .= '<li>'.$tl.'</li>';
		}
		$transl .= '</ul>\');return false;"><img src="../artwork/information_icon.gif" class="help_tip" /></a>';
	}
	if ($data_part2!='') {
		if ($data_index=='-1') {
      foreach ($data_part1 as $data_key => $data_element) {
        $display_text .= '<tr><td>';
        if (isset($data_part1[$data_key])) $display_text .= '<em>' . $data_part1[$data_key] . '</em>';
        $display_text .= '</td><td>';
        if (isset($data_part2)) {
          if ($data_part2 != $last_key) {
            $display_text .= '<strong>' . $data_part2 . '</strong>';
          }
          else {
            $display_text .= '<span style="color:lightgrey"><strong>&nbsp;&nbsp;&nbsp;-<small>//</small>-</strong></span>';
          }
        }
        $display_text .= '</td><td>';
        if (isset($data_part3[$data_key])) {
          if ($data_part3[$data_key] != $last_value) {
            if ($data_part2 != $last_key) {
              $display_text .= $data_part3[$data_key];
            }
            else {
              $display_text .= '<span style="color:IndianRed">' . $data_part3[$data_key] . '</span>';
            }
          }
          else {
            $display_text .= '<span style="color:lightgrey"><strong>&nbsp;&nbsp;&nbsp;-<small>//</small>-</strong></span>';
          }
        }
        $display_text .= '</td></tr>';
        $last_key = $data_part2;
        $last_value = $data_part3[$data_key];
			}
		} else {
			$display_text .= '<tr>';
			$display_text .= '<td><em>' . $data_part1[$data_index] . '</em></td>';
			$display_text .= '<td><strong>' . $data_part2 . '</strong>';
			if ($global_for_translated_list) $display_text .= $transl;
			$display_text .= '</td>';
			//$display_text .= '<td>' . $data_part3[$data_index] . '</td>';
			$display_text .= '<td>' . $data_part3[$data_index] . '</td>';
			$display_text .= '</tr>';
		}
	}
}

//----------------------------------------------------------------------------------------------

//exclusion list
$excluded = explode("|",  ".|..|.ds_store|.svn|en");

//path for lang list
$path = preg_replace('/testing/', '', getcwd()) . 'lang/';

//list of lang folders
$lang_array = array();

$folder_handle = opendir($path);
while (false !== ($filename = readdir($folder_handle))) {
  if (!in_array(strtolower($filename), $excluded)) {
    if (is_dir($path . $filename . "/")) {
      array_push($lang_array, $filename);
    }
  }
}

//show just the lang specified by '?lang=...'
$spec_lang = '';
if (isset($_GET['lang'])) $spec_lang = $_GET['lang'];

//path for folders inside /en/
$path = preg_replace('/testing/', '', getcwd()) . 'lang/en/';
$global_for_translated_list = false;

//searching for files
$files = Array();
$paths = Array();
$paths = file_array($path, $excluded);

//test list of searched files
$strings_en = file_array_read($files, 'en');
$required_en = $required;
foreach ($required_en as $k => $v) $required_en[$k]++;
if (empty($strings_en[0])) unset($strings_en[0]);

foreach ($lang_array as $lang) {
  if ($spec_lang == '' OR $spec_lang == $lang) {
    $required = $required_en;
    $strings_pl = file_array_read($files, $lang);
    if (empty($strings_pl[0])) unset($strings_pl[0]);

    echo '<h2 class="midblue_header">';
		echo '<a id="bs_' . $lang . '" style="display:none" onclick=showhide("' . $lang . '");><img src="../artwork/blue_plus.png" class="plusminus" /></a>';
		echo '<a id="bh_' . $lang . '" onclick=showhide("' . $lang . '");><img src="../artwork/blue_minus.png" class="plusminus" /></a> ';
		echo 'Analysis for: ' . $lang ;
		echo '</h2>';
    echo '<div id="' . $lang . '">';
    //Missing files
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_pl as $strings_key => $strings_data) {
      if ($strings_data[0] == $strings_key) $display_text .= '<em>' . $strings_data[0] . '</em><br />';
    }
    echo '<h3>Missing files: <img src="../artwork/information_icon.gif" class="help_tip" title="A list of files in \'en\' with no corresponding files in \''. $lang.'\'" /></h3>';
    if ($display_text=='') $display_text='<tr><td>none</td></tr>';
    echo '<table>'.$display_text.'</table>';
    
    //Strings from missing files
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data) {
      $data_path = explode("|", $strings_data[0]);
      foreach ($data_path as $data_path_key => $data_path_elem) {
        if (isset($strings_pl[$data_path_elem])) display_this($strings_data, $data_path_key);
      }
    }
    echo '<h3>Missing "require" lines: <img src="../artwork/information_icon.gif" class="help_tip" title="Lines requiring other files in \'en\' that are missing in \''. $lang.'\' file" /></h3>';
		$display_text = '';
		foreach ($required as $k => $v) {
			if ($v==2) {
				$l = explode('|',$k);
				$display_text.='<tr><td><strong>'.$l[0].'</strong></td><td>'.$l[1].'</td></tr>';
			}
		}
    if ($display_text=='') $display_text='<tr><td>none</td></tr>';
    echo '<table>'.$display_text.'</table>';
    
		echo '<h3>Extensive "require" lines: <img src="../artwork/information_icon.gif" class="help_tip" title="Lines requiring other files in \''. $lang.'\' that are missing in \'en\' file" /></h3>';
		$display_text = '';
		foreach ($required as $k => $v) {
			if ($v==1) {
				$l = explode('|', $k);
				$display_text .= '<tr><td><strong>'.$l[0].'</strong></td><td>'.$l[1].'</td></tr>';
			}
		}
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    echo '<h3>Strings from missing files: <img src="../artwork/information_icon.gif" class="help_tip" title="A list of strings taken from files in \'en\' that have no corresponding files in \''. $lang.'\'" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    //Missing strings
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data)
      if (!isset($strings_pl[$strings_key]) and (!isset($strings_pl[$strings_data[0]]))) display_this($strings_data, -1);
    echo '<h3>Missing strings: <img src="../artwork/information_icon.gif" class="help_tip" title="A list of strings from files in \'en\' missing from corresponding files in \''. $lang.'\'" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    //Excessive strings
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_pl as $strings_key => $strings_data)
      if (!isset($strings_en[$strings_key]) and (!isset($strings_pl[$strings_data[0]]))) display_this($strings_data, -1);
    echo '<h3>Excessive strings: <img src="../artwork/information_icon.gif" class="help_tip" title="A list of strings from files in \''. $lang.'\' missing from from corresponding files in \'en\'" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    //Strings missing from file
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data) {
      if (isset($strings_pl[$strings_key]) and ($strings_pl[$strings_key][0] != $strings_data[0])) {
        $data_path1 = explode("|", $strings_data[0]);
        $data_path2 = explode("|", $strings_pl[$strings_key][0]);
        $data_path3 = array_diff($data_path1, $data_path2);
        if (count($data_path3)>0) display_this(Array(implode(", ", $data_path3), $strings_data[1], $strings_data[2], $strings_data[3]), -1);
      }
    }   
    echo '<h3>Strings missing from file: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of strings existing in multiple files where the list of it\'s files from \'en\' is not the same as the list it\'s files from \''. $lang.'\'" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

		//Strings extensive in file
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data) {
      if (isset($strings_pl[$strings_key]) and ($strings_pl[$strings_key][0]!=$strings_data[0])) {
        $data_path1 = explode("|", $strings_data[0]);
        $data_path2 = explode("|", $strings_pl[$strings_key][0]);
        $data_path3 = array_diff($data_path2, $data_path1);
				if (count($data_path3)>0) display_this(Array(implode(", ", $data_path3), $strings_data[1], $strings_data[2], $strings_data[3]), -1);
      }
    }   
    echo '<h3>Strings extensive in file: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of strings existing in multiple files where the list of it\'s files from \''. $lang.'\' is not the same as the list it\'s files from \'en\'" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';
		
    //Files with empty keys for the \'string\' array
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_pl as $strings_key => $strings_data) {
      if ($strings_data[1] == '') {
      $data_path1 = explode("|", $strings_data[0]);
      foreach ($data_path1 as $data_path1_key => $data_path1_elem) {
          $display_text .= '<em>' . $data_path1_elem . '</em><br />';
        }
      }
    }
    echo '<h3>Files with empty keys for the \'string\' array: <img border="0" src="../artwork/information_icon.gif" class="help_tip" title="A list of strings from \''. $lang.'\' with empty keys for the \'string\' array"></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    //Duplicate strings in files
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_pl as $strings_key => $strings_data)	{
      if ($strings_pl[$strings_key][3] > 1)	{
        $data_path1 = explode("|", $strings_data[0]);
        $data_path3 = array_unique($data_path1);
        $data_path3 = array_count_values($data_path1);
        if (count($data_path3)!=count($data_path1)) {
          foreach ($data_path3 as $data_path3_key => $data_path3_elem) {
            if ($data_path3_elem>1) {
              display_this(Array($data_path3_key, $strings_data[1], $strings_data[2], $strings_data[3]), -1);
            }
          }
        }
      }
    }
    echo '<h3>Duplicate strings in files: <img border="0" src="../artwork/information_icon.gif" class="help_tip" title="a list of strings with the same key as other within the same file in \''. $lang.'\' "></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';

    //Identical texts
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data) 
      if (isset($strings_pl[$strings_key]) and $strings_pl[$strings_key]==$strings_en[$strings_key] and strpos($strings_data[2],'&lt;&lt;&lt;')===false) display_this($strings_data, -1);
    echo '<h3>Identical texts: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of not translated strings (identical in \'en\' and \''. $lang.'\')" /></h3>';
    if ($display_text=='') $display_text='<tr><td>none</td></tr>';
    echo '<table>'.$display_text.'</table>';

    //Identical strings in files
    $last_key = '';
    $last_value = '';
    $display_text = '';
    foreach ($strings_en as $strings_key => $strings_data)	{
      if ($strings_en[$strings_key][3]>1) {
        $data_path1 = explode("|", $strings_data[2]);
        $data_path3 = Array();
				if (isset($strings_pl[$strings_key][2])) $data_path3 = explode("|", $strings_pl[$strings_key][2]);
        if (count($data_path3)==count($data_path1)) {
					$global_for_translated_list = true;
          foreach ($data_path1 as $data_path1_key => $data_path1_elem) {
            if (($data_path1[$data_path1_key]==$data_path3[$data_path1_key]))	{
            display_this($strings_pl[$strings_key], $data_path1_key);
            //var_dump($strings_key,$strings_pl[$strings_key],'xxxx');
						}
          }
					$global_for_translated_list = false;
        }
      }
    }
    echo '<h3>Identical strings: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of not translated strings across the whole folder (identical in any of files in \'en\' and \''. $lang.'\') - possibly being translated in some other file" /></h3>';
    if ($display_text == '') {
      $display_text = '<tr><td>none</td></tr>';
    }
    echo '<table>'.$display_text.'</table>';
		echo '</div>';
  }
}
 
//---------------------------------------------------------------------------

echo '<hr>';
echo '<h2 class="midblue_header">';
echo '<a id="bs_en" style="display:none" onclick=showhide("en");><img src="../artwork/blue_plus.png" class="plusminus" /></a>';
echo '<a id="bh_en" onclick=showhide("en");><img src="../artwork/blue_minus.png" class=plusminus" /></a> ';
echo 'Analysis for: en';
echo '</h2>';
echo '<div id="en">';

// Files with empty keys for the \'string\' array
$last_key = '';
$last_value = '';
$display_text = '';
foreach ($strings_en as $strings_key => $strings_data)	{
	if ($strings_data[1]=='')	{
    $data_path1 = explode("|", $strings_data[0]);
    foreach ($data_path1 as $data_path1_key => $data_path1_elem) {
      $display_text .= '<em>' . $data_path1_elem . '</em><br />';
    }
  }
}
echo '<h3>Files with empty keys for the \'string\' array: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of strings from \'en\' with empty keys for the \'string\' array" /></h3>';
if ($display_text == '') {
  $display_text = '<tr><td>none</td></tr>';
}
echo '<table>'.$display_text.'</table>';

// Duplicate strings in files
$last_key = '';
$last_value = '';
$display_text = '';
foreach ($strings_en as $strings_key => $strings_data)	{
	if ($strings_en[$strings_key][3]>1) {
    $data_path1 = explode("|", $strings_data[0]);
    $data_path3 = array_unique($data_path1);
    $data_path3 = array_count_values($data_path1);
    if (count($data_path3) != count($data_path1)) {
      foreach ($data_path3 as $data_path3_key => $data_path3_elem) {
        if ($data_path3_elem > 1) {
          display_this(Array($data_path3_key, $strings_data[1], $strings_data[2], $strings_data[3]), -1);
        }
      }
    }
  }
}
echo '<h3>Duplicate strings: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of strings with the same key within the same file in \'en\'" /></h3>';
if ($display_text == '') {
  $display_text='<tr><td>none</td></tr>';
}
echo '<table>'.$display_text.'</table>';

// Duplicate strings
$last_key = '';
$last_value = '';
$display_text = '';
foreach ($strings_en as $strings_key => $strings_data)	{
	if ($strings_en[$strings_key][3]>1) {
		display_this($strings_data, -1);
	}
}
echo '<h3>Duplicate strings across files: <img src="../artwork/information_icon.gif" class="help_tip" title="a list of strings with the same key across the files in \'en\'" /></h3>';
if ($display_text == '') {
  $display_text='<tr><td>none</td></tr>';
}
echo '<table>'.$display_text.'</table>';
echo '</div>';

echo '<hr />';
echo '<div id="trans"></div>';
?>
</body>
</html>
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

require '../include/sysadmin_auth.inc';
require '../include/sidebar_menu.inc';
require '../include/errors.inc';
require '../include/year_tabs.inc';

$current_year = check_var('calyear', 'GET', true, false, true);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

	<title>Rog&#333;: <?php echo $string['summativeexamfeedback']  . ' ' . $configObject->get('cfg_install_type'); ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/statistics.css" />
	<link rel="stylesheet" type="text/css" href="../css/tabs.css" />
	
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
</head>

<body>
<?php
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>
  <div id="content">  
  
<div class="head_title">
  <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../admin/index.php"><?php echo $string['administrativetools'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../statistics/index.php"><?php echo $string['statistics'] ?></a></div>
  <div class="page_title"><?php echo $string['summativeexamfeedback'] ?>: <span style="font-weight:normal"><?php echo $_GET['calyear']; ?>/<?php echo (substr($_GET['calyear'],2,2)+1); ?></span></div>
</div>

<table class="header">
<tr>
<th style="text-align:right" colspan="2"><div style="text-align:right; vertical-align:bottom"><?php echo drawTabs($current_year, 'academic', 6, 1) ?></div></th>
</tr>
<tr><td colspan="2" style="border:0px; background-color:#1E3C7B; height:5px"></td></tr>
</table>

<blockquote>
<table class="stats">
<tr>
<th><?php echo $string['school'] ?></th>
<th><?php echo $string['exams'] ?></th>
<th><?php echo $string['objectivefeedback'] ?></th>
<th><?php echo $string['questionfeedback'] ?></th>
<th><?php echo $string['cohortperformance'] ?></th>
<th><?php echo $string['externalexaminers'] ?></th>
</tr>
<?php
$master_array = array();

$result = $mysqli->prepare("SELECT schools.id, school, name FROM schools, faculty WHERE schools.facultyID = faculty.id AND school != 'Training' AND schools.deleted IS NULL AND faculty.deleted IS NULL ORDER BY name, school");
$result->execute();
$result->bind_result($id, $school, $faculty);
while ($result->fetch()) {
  $master_array[$school]['id'] = $id;
  $master_array[$school]['faculty'] = $faculty;
	$master_array[$school]['exams'] = 0;
	$master_array[$school]['objectives'] = 0;
	$master_array[$school]['questions'] = 0;
	$master_array[$school]['cohort_performance'] = 0;
	$master_array[$school]['external_examiner'] = 0;
}
$result->close();

foreach ($master_array as $school => $data) {
	// Get the modules which belong in the school first.
	$moduleIDs = array();

	$result = $mysqli->prepare("SELECT id FROM modules WHERE schoolid = ? AND active = 1 AND mod_deleted IS NULL");
	$result->bind_param('i', $data['id']);
	$result->execute();
	$result->bind_result($id);
	while ($result->fetch()) {
		$moduleIDs[] = $id;
	}
	$result->close();
	
	$master_array[$school]['module_no'] = count($moduleIDs);

	if (count($moduleIDs) > 0) {
		// Get the papers.
		$date_range = '';
		if ($_GET['calyear']) {
		  $year = $_GET['calyear'];
		
			$date_range .= " AND start_date > {$year}0901000000 AND end_date <= " . ($year + 1) . "0831235959";  // Start and end within year			
		}
		
		$result = $mysqli->prepare("SELECT DISTINCT properties.property_id, paper_title, paper_type FROM properties, properties_modules WHERE properties.property_id = properties_modules.property_id $date_range AND paper_type = '2' AND idMod IN (" . implode(',', $moduleIDs) . ") AND deleted IS NULL GROUP BY property_id");
		$result->execute();
		$result->bind_result($paperID, $paper_title, $paper_type);
		while ($result->fetch()) {
			$master_array[$school]['exams']++;
		}
		$result->close();

		$result = $mysqli->prepare("SELECT DISTINCT idfeedback_release, type FROM properties, properties_modules, feedback_release WHERE feedback_release.paper_id = properties.property_id AND properties.property_id = properties_modules.property_id $date_range AND paper_type = '2' AND idMod IN (" . implode(',', $moduleIDs) . ") AND deleted IS NULL");
		$result->execute();
		$result->bind_result($idfeedback_release, $type);
		while ($result->fetch()) {
			$master_array[$school][$type]++;
		}
		$result->close();
	}
}

$parts = array('exams', 'objectives', 'questions', 'cohort_performance', 'external_examiner');

$old_faculty = '';
$faculty_stats = array(0, 0, 0, 0, 0);

foreach ($master_array as $school => $data) {
  if ($old_faculty != $data['faculty']) {
	  if ($old_faculty != '') {
			echo output_faculty_stats($faculty_stats);
	  }
		echo '<tr><td colspan="6" class="faculty">' . $data['faculty'] . '</td></tr>';
		$faculty_stats = array(0, 0, 0, 0, 0);
	}
  echo "<tr><td>" . $school . "</td>";
	
	for ($i=0; $i<5; $i++) {
	  $part = $parts[$i];
	  if ($data[$part] == 0) {
			echo "<td class=\"n grey\">" . $data[$part] . "</td>";
		} else {
			if ($i == 1 or $i == 2 or $i == 3) {
				echo "<td class=\"n\"><a href=\"feedback_detail.php?calyear=" . $_GET['calyear'] . "&school=" . $master_array[$school]['id'] . "&type=$i\">" . $data[$part] . "</a></td>";
			} else {
				echo "<td class=\"n\">" . $data[$part] . "</td>";
			}
		}
		$faculty_stats[$i] += $data[$part];
	}
	echo "</tr>\n";
	
	$old_faculty = $data['faculty'];
}
?>
</table>
</blockquote>
</div>
</body>
</html>
<?php
function output_faculty_stats($stats) {
  $html = '<tr><td>&nbsp;</td>';
	
	for ($i=0; $i<5; $i++) {
	  $html .= '<td class="n subtotal">' . number_format($stats[$i]) . '</td>';
	}
	
	$html .= '</tr>';
	
	return $html;
}
?>
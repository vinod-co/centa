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
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

	<title>Rog&#333;: <?php echo $string['questionsbyschool']  . ' ' . $configObject->get('cfg_install_type'); ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/statistics.css" />
	<style type="text/css">
		.qtype {width:4%}
	</style>
	
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
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home']; ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../admin/index.php"><?php echo $string['administrativetools']; ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../statistics/index.php"><?php echo $string['statistics']; ?></a></div>
  <div class="page_title"><?php echo $string['questionsbyschool']; ?></div>
</div>

<table class="stats">
<tr>
<th><?php echo $string['school'] ?></th>
<?php
	$types = array('area', 'dichotomous', 'enhancedcalc', 'extmatch', 'blank', 'hotspot', 'info', 'labelling', 'likert', 'matrix', 'mcq', 'mrq', 'keyword_based', 'random', 'rank', 'sct', 'textbox', 'true_false');
  foreach ($types as $type) {
	  echo '<th class="qtype">' . $string[$type] . '</th>';
	}
?>
</tr>
<?php
$master_array = array();

$result = $mysqli->prepare("SELECT schools.id, school, name FROM schools, faculty WHERE schools.facultyID = faculty.id AND school != 'Training' AND schools.deleted IS NULL AND faculty.deleted IS NULL ORDER BY name, school");
$result->execute();
$result->bind_result($id, $school, $faculty);
while ($result->fetch()) {
  $master_array[$school]['id'] = $id;
  $master_array[$school]['faculty'] = $faculty;
	$master_array[$school]['types'] = array('blank'=>0, 'dichotomous'=>0, 'flash'=>0, 'hotspot'=>0, 'labelling'=>0, 'likert'=>0, 'matrix'=>0, 'mcq'=>0, 'mrq'=>0, 'rank'=>0, 'textbox'=>0, 'info'=>0, 'extmatch'=>0, 'random'=>0, 'sct'=>0, 'keyword_based'=>0, 'true_false'=>0, 'area'=>0, 'enhancedcalc'=>0);
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
				
		$result = $mysqli->prepare("SELECT DISTINCT questions.q_id, q_type FROM questions, questions_modules WHERE questions.q_id = questions_modules.q_id AND idMod IN (" . implode(',', $moduleIDs) . ") AND deleted IS NULL GROUP BY questions.q_id");
		$result->execute();
		$result->bind_result($q_id, $q_type);
		while ($result->fetch()) {
			$master_array[$school]['types'][$q_type]++;
		}
		$result->close();
	}
}

$old_faculty = '';
$faculty_stats = $types;

foreach ($master_array as $school => $data) {
  if ($old_faculty != $data['faculty']) {
	  if ($old_faculty != '') {
			echo output_faculty_stats($faculty_stats, $types);
	  }
		echo '<tr><td colspan="19" class="faculty">' . $data['faculty'] . '</td></tr>';
		$faculty_stats = array();
	}
  echo "<tr><td>" . $school . "</td>";
	
	foreach ($types as $type) {
	  if ($data['types'][$type] == 0) {
			echo "<td class=\"n grey\">" . $data['types'][$type] . "</td>";
		} else {
			echo "<td class=\"n\">" . number_format($data['types'][$type]) . "</td>";
		}
		if (isset($faculty_stats[$type])) {
			$faculty_stats[$type] += $data['types'][$type];
		} else {
			$faculty_stats[$type] = $data['types'][$type];
		}
	}
	echo "</tr>\n";

	$old_faculty = $data['faculty'];
}
?>
</table>

</div>
</body>
</html>
<?php
function output_faculty_stats($stats, $types) {
  $html = '<tr><td>&nbsp;</td>';
	
	foreach ($types as $type) {
	  $html .= '<td class="n subtotal">' . number_format($stats[$type]) . '</td>';
	}
	
	$html .= '</tr>';
	
	return $html;
}
?>
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
* Used by a member of staff to view a previously marked student OSCE sheet.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/staff_student_auth.inc';
require '../include/demo_replace.inc';
require './osce.inc';
require_once '../classes/paperproperties.class.php';
require_once '../classes/killer_question.class.php';

if ($userObject->has_role('Demo')) $demo = true;

$msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));

if ($userObject->has_role(array('Staff', 'Admin', 'SysAdmin'))) {
  $userID = $_GET['userID'];
  $propertyObj = PaperProperties::get_paper_properties_by_id($_GET['paperID'], $mysqli, $string);
  $paperID = $_GET['paperID'];
} elseif ($userObject->has_role('Student')) {
  $userID = $userObject->get_user_ID();
  $propertyObj = PaperProperties::get_paper_properties_by_crypt_name($_GET['id'], $mysqli, $string, true);
    
  if (!$propertyObj->is_question_fb_released()) {
    $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
  }
  $paperID = $propertyObj->get_property_id();
} else {
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
}

$killer_questions = new Killer_question($paperID, $mysqli);
$killer_questions->load();

$killed = false;

// Get the module ID and calendar year of the OSCE station.
$result = $mysqli->prepare("SELECT username, title, surname, first_names, grade, yearofstudy, student_id FROM (users, sid) WHERE users.id = ? AND users.id = sid.userID");
$result->bind_param('i', $userID);
$result->execute();
$result->bind_result($username, $title, $surname, $first_names, $grade, $year, $student_id);
$result->fetch();
$result->close();

$original_username = $username;
if (isset($demo) and $demo == true) {
  $surname = demo_replace($surname, $demo);
  $first_names = demo_replace($first_names, $demo);
  $student_id = demo_replace_number($student_id, $demo);
}

$paper_title  = $propertyObj->get_paper_title();
$bgcolor      = $propertyObj->get_bgcolor();
$fgcolor      = $propertyObj->get_fgcolor();
$labelcolor   = $propertyObj->get_labelcolor();
$themecolor   = $propertyObj->get_themecolor();
$marking      = $propertyObj->get_marking();
?>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title><?php echo $string['osceform']; ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/osce.css" />
  <style type="text/css">
    body {background-color: <?php echo $bgcolor; ?>; color: <?php echo $fgcolor; ?>; font-size: 90%; margin-bottom: 10px}
    .t {color: <?php echo $themecolor; ?>}
  </style>
</head>
  
<body>
  <table cellpadding="0" cellspacing="0" border="0" style="width:100%"><tr>
<?php
$demo = true;
  if (file_exists('../users/photos/' . $original_username . '.jpg')) {
    $photo_size = getimagesize($cfg_web_root . 'users/photos/' . $username . '.jpg');
    if (isset($demo) and $demo == true) {
      echo '<td class="photo"><img src="../users/pixel_photo.php?username=' . $username . '" ' . $photo_size[3] . ' alt="Photo" /></td>';
    } else {
      echo '<td style="width:180px"><img src="../users/photos/' . $original_username . '.jpg" ' . $photo_size[3] . ' alt="Photo" /></td>';
    }
  } else {
    echo '<td></td>';
  }
  echo "<td style=\"vertical-align:top; text-align:left\"><div class=\"osce_title\">$paper_title</div><div class=\"student_name\">$title $surname, <span style=\"color:#808080\">$first_names</span></div><span class=\"student_id\">($student_id)</span></td></table>\n<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" style=\"width: 100%\"><tr>";

  // Query Log4 just in case form has already been submitted for this user.
  $result = $mysqli->prepare("SELECT id, feedback, overall_rating FROM log4_overall WHERE q_paper = ? AND userID = ?");
  $result->bind_param('ii', $paperID, $userID);
  $result->execute();
  $result->bind_result($log4_overall_id, $feedback, $overall_rating);
  $result->fetch();
  $result->close();

  $stored_results = array();
  $result = $mysqli->prepare("SELECT q_id, rating, q_parts FROM log4 WHERE log4_overallID = ?");
  $result->bind_param('i', $log4_overall_id);
  $result->execute();
  $result->bind_result($q_id, $rating, $q_parts);
  while ($result->fetch()) {
    $stored_results[$q_id] = $rating;
    $stored_q_parts[$q_id] = $q_parts;
  }
  $result->close();
  
  // Get the questions.
  $question_no = 1;
  $sub_totals = array(0=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0);
  $cell_colors = array('#FF8080', '#FFC169', '#50E850');
  $rating_class = array('rating1', 'rating2', 'rating3');
  
  $result = $mysqli->prepare("SELECT q_id, q_type, theme, notes, scenario, leadin, display_method FROM papers, questions WHERE paper = ? AND papers.question = questions.q_id ORDER BY display_pos");
  $result->bind_param('i', $paperID);
  $result->execute();
  $result->bind_result($q_id, $q_type, $theme, $notes, $scenario, $leadin, $display_method);
  while ($result->fetch()) {
    if ($question_no == 1) {
      // Header row
      $cols = substr_count($display_method, '|');
    }
    if (trim($theme) != '') {
      echo "<tr><td colspan=\"4\" class=\"t\">$theme</td></tr>\n";
    }
		echo "<tr id=\"row_" . $question_no . "\">";
		if ($killer_questions->is_killer_question($q_id)) {
      if (array_key_exists($q_id, $stored_results) and $stored_results[$q_id] == 0) {
				echo "<td class=\"killerq skull\">";
				$killed = true;
			} else {
				echo "<td class=\"q skull\">";
			}
		} else {
			echo "<td class=\"q\">";
		}
    if (trim($notes) != '') {
      echo "<span style=\"color:$labelcolor\"><img src=\"../artwork/notes_icon.gif\" width=\"16\" height=\"16\" alt=\"note\" />&nbsp;$notes</span><br />\n";
    }
 
    echo parse_leadin($leadin, $stored_q_parts[$q_id]) . "</td>";
    $sub_totals[$stored_results[$q_id]]++; 
    for ($i=0; $i<$cols; $i++) {
      if (array_key_exists($q_id, $stored_results) and $stored_results[$q_id] == $i) {
        echo "<td class=\"" . $rating_class[$i] . " r\">$i</td>";
      } else {
        echo "<td class=\"r\">$i</td>";
      }
    }
    echo "</tr>\n";
    $question_no++;
  }
  echo "<tr><td></td>";
  for ($i=0; $i<$cols; $i++) {
    echo "<td class=\"rating\"><input type=\"text\" name=\"fails\" size=\"4\" style=\"border:0px; text-align:right\" value=\"" . $sub_totals[$i] . "\" /></td>";
  }  
  echo "</tr></table>\n<br /><div><strong>" . $string['overallclassification'] . "</strong></div><input type=\"hidden\" name=\"overallscore\" id=\"overallscore\" value=\"0\" /><table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" style=\"width:100%\"><tr id=\"row_overall\">";
  $result->close();
	
  switch ($marking) {
    case '3':
      $labels = array('Clear Fail', 'Borderline', 'Clear Pass');
      $colors = array('#D99594', '#FABF8F', '#C2D69B');
      break;
    case '4':
      $labels = array('Fail', 'Borderline Fail', 'Borderline pass', 'Pass', 'Good Pass');
      $colors = array('#D99694', '#E5B9B7', '#FFC169', '#D7E3BC', '#C2D69B');
      break;
    case '5':
      $labels = array('Unsatisfactory', 'Competent');
      $colors = array('#D99594', '#C2D69B');
      break;
    case '6':
      $labels = array('Clear FAIL', 'BORDERLINE', 'Clear PASS', 'Honours PASS');
      $colors = array('#D99694', '#E5B9B7', '#D7E3BC', '#C2D69B');
      break;
    case '7':
      $labels = array('Fail', 'Pass');
      $colors = array('#D99694', '#C2D69B');
      break;
  }

	// Killer Question check - final rating.
	if ($killed) {
		$overall_rating = 1;			// Fail the whole OSCE if any killer question is zero.
	}

  for ($i=0; $i<count($labels); $i++) {
    if ($overall_rating == ($i+1)) {
      echo "<td class=\"overall\" style=\"background-color:" . $colors[$i] . "\">" . $string[strtolower($labels[$i])] . "</td>\n";
    } else {
      echo "<td class=\"overall\">" . $string[strtolower($labels[$i])] . "</td>\n";
    }
  }
  ?>
  </tr></table>  

  <br />
  <blockquote>
  <div><strong><?php echo $string['feedback']; ?></strong></div>
  <textarea name="feedback" id="feedback" style="border:1px solid #C0C0C0; width:100%" cols="60" rows="4"><?php echo stripslashes($feedback); ?></textarea>
  </blockquote>
<?php
  $mysqli->close();
?>
</body>
</html>

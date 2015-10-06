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
* Completes final log of the last screen to the 'logX' table and then will display feedback if the paper is in 'formative'
* mode or will display a confirmation notice to the examinee stating all answers and marks have been successfully recorded.
*
* @author Simon Wilkinson, Anthony Brown
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/staff_student_auth.inc';
require_once '../include/marking_functions.inc';
require_once '../include/calculate_marks.inc';
require_once '../include/errors.inc';
require_once '../include/mapping.inc';
require_once '../include/media.inc';
require_once '../include/finish_functions.inc';
require_once '../include/paper_security.inc';
require_once '../classes/paperutils.class.php';
require_once '../classes/logmetadata.class.php';
require_once '../classes/paperproperties.class.php';
require_once '../classes/mathsutils.class.php';
require_once '../classes/log_lab_end_time.class.php';
require_once '../classes/question_status.class.php';
require_once '../include/demo_replace.inc';
require_once '../classes/exam_announcements.class.php';
require_once '../LTI/ims-lti/UoN_LTI.php';

//HTML5 part
require_once '../lang/' . $language . '/question/edit/hotspot_correct.txt';
require_once '../lang/' . $language . '/question/edit/area.txt';
require_once '../lang/' . $language . '/paper/hotspot_answer.txt';
require_once '../lang/' . $language . '/paper/hotspot_question.txt';
require_once '../lang/' . $language . '/paper/label_answer.txt';
$jstring = $string; //to pass it to JavaScript HTML5 modules
//HTML5 part

check_var('id', 'GET', true, false, false);

$demo		= is_demo($userObject);
$userID = $userObject->get_user_ID();

//get the paper properties
$propertyObj = PaperProperties::get_paper_properties_by_crypt_name($_GET['id'], $mysqli, $string, true);

/*
* Set the default colour scheme for this paper and allow current users' special settings to override
* $bgcolor, $fgcolor, $textsize, $marks_color, $themecolor, $labelcolor, $font, $unanswered_color are passed by reference!!
*/
$bgcolor = $fgcolor = $textsize = $marks_color = $themecolor = $labelcolor = $font = $unanswered_color = $dismisscolor = '';
$propertyObj->set_paper_colour_scheme($userObject, $bgcolor, $fgcolor, $textsize, $marks_color, $themecolor, $labelcolor, $font, $unanswered_color, $dismisscolor);

$paperID                    = $propertyObj->get_property_id();
$labs                       = $propertyObj->get_labs();
$calendar_year              = $propertyObj->get_calendar_year();
$display_correct_answer     = $propertyObj->get_display_correct_answer();
$display_question_mark      = $propertyObj->get_display_question_mark();
$display_students_response  = $propertyObj->get_display_students_response();
$display_feedback           = $propertyObj->get_display_feedback();
$hide_if_unanswered         = $propertyObj->get_hide_if_unanswered();
$paper_title                = $propertyObj->get_paper_title();
$paper_type                 = $propertyObj->get_paper_type();
$start_date                 = $propertyObj->get_start_date();
$end_date                   = $propertyObj->get_end_date();
$marking                    = $propertyObj->get_marking();
$paper_postscript           = $propertyObj->get_paper_postscript();
$pass_mark                  = $propertyObj->get_pass_mark();
$latex_needed               = $propertyObj->get_latex_needed();
$password                   = $propertyObj->get_password();
$moduleID                   = $propertyObj->get_modules();

$show_feedback              = can_display_feedback($paper_type, $moduleID, $userObject);

$attempt = 1; // Default attempt to 1 overwritten if the student is resit candidate

$log_type = $paper_type;    // Set log_type to current type of the paper.

if (isset($_GET['log_type']) and (($_GET['log_type'] == '0' or $_GET['log_type'] == '1') or $userObject->has_role(array('SysAdmin', 'Admin', 'Staff')))) {  // If paper Formative/Progress Test allow override by $_GET.
  $log_type = $_GET['log_type'];
}

$low_bandwidth = 0;
$lab_id = null;

// Get lab info
$current_address = NetworkUtils::get_client_address();
$lab_factory = new LabFactory($mysqli);
if ($lab_object = $lab_factory->get_lab_based_on_client($current_address)) {
  $lab_name = $lab_object->get_name();
  $lab_id = $lab_object->get_id();
}

$summative_exam_session_started = false;
$paper_scheduled = ($propertyObj->get_start_date() !== null);
if ($propertyObj->get_exam_duration() != null and $propertyObj->get_paper_type() == '2') {
  // Has this lab had an end time set?
  $log_lab_end_time = new LogLabEndTime($lab_id, $propertyObj, $mysqli);
  $summative_exam_session_started = $log_lab_end_time->get_session_end_date_datetime();
}

if ($userObject->has_role(array('External Examiner'))) {
  // No further security checks.
  require_once '../classes/reviews.class.php';
  if (!ReviewUtils::is_external_on_paper($userObject->get_user_ID(), $paperID, $mysqli)) {
    $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
    $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['accessdenied'], '/artwork/page_not_found.png', '#C00000', true, true);
  }
} elseif (($userObject->has_role('Staff') and check_staff_modules($moduleID, $userObject)) or $userObject->has_role('SysCron')) {
  // No further security checks.
} else {
  $modIDs = array_keys($moduleID);

  if ($paper_type == 2) $latex_needed = 0;  // Students get no feedback for summative exams so don't load the Latex library

  // Check for additional password on the paper
  check_paper_password($password, $string, $mysqli);

  // Check time security
  check_datetime($start_date, $end_date, $string, $mysqli);

  // Check room security
  $low_bandwidth = check_labs($paper_type, $labs, $current_address, $password, $string, $mysqli);

  // Get modules if the user is a student and the paper is not formative
  $attempt = check_modules($userObject, $modIDs, $calendar_year, $string, $mysqli);

  // Check for any metadata security restrictions
  check_metadata($paperID, $userObject, $modIDs, $string, $mysqli);

  if (time() > $end_date and ($paper_type == '1' or ($paper_type == '2' and $paper_scheduled and $summative_exam_session_started === false)) ) {
    $paper_type = '_late';
  }
}

// Are we in a staff test and preview mode?
$is_preview_mode = ($userObject->has_role(array('Staff', 'SysAdmin')) and isset( $_REQUEST['mode'] ) and $_REQUEST['mode'] == 'preview');
$is_summative_preview_mode = ($is_preview_mode and $propertyObj->get_paper_type() == '2');

// Are we in a staff test and preview mode and on the first screen?
$is_preview_mode_first_launch = ($is_preview_mode == true and isset($_GET['mode']) and $_GET['mode'] == 'preview');

// Are we in a staff single question testmode?
$is_question_preview_mode = (isset($_GET['q_id']));

$is_exam_review_mode = ($userObject->has_role(array('Staff', 'External Examiner')) and isset($_GET['userID']) and $_GET['userID'] != $userObject->get_user_ID());

$is_formative_review = (isset($_GET['metadataID']) and $paper_type == '0');

if ($is_exam_review_mode or $is_question_preview_mode or $is_summative_preview_mode) {
  // Turn on all feedback if staff and a student exam script is being reviewed.
  $display_correct_answer     = 1;
  $display_question_mark      = 1;
  $display_students_response  = 1;
  $display_feedback           = 1;
  $hide_if_unanswered         = 0;
  $is_exam_review_mode        = true;
}

if (isset($_GET['userID'])) {
  if ($userObject->has_role(array('SysAdmin', 'Admin', 'Staff', 'External Examiner'))) {
    $log_metadata = new LogMetadata($_GET['userID'], $paperID, $mysqli);
  } else {   // Student is hacking the userid parameter.
    $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
    $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
  }
} else {
  $log_metadata = new LogMetadata($userObject->get_user_ID(), $paperID, $mysqli);
}
if (isset($_GET['metadataID'])) {
  $log_metadata->get_record($_GET['metadataID']);
  $metadataid = $_GET['metadataID'];
} else {
  $log_metadata->get_record();
  $metadataid = $log_metadata->get_metadata_id();
}

if (!$is_exam_review_mode and !$is_question_preview_mode and !$is_formative_review) {
  // Only update log metadata if we are ending an exam.
  $log_metadata->set_completed_to_now();
}

require '../config/finish.inc';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="imagetoolbar" content="false">

<title><?php echo $string['examscript'] . ' ' . $configObject->get('cfg_install_type'); ?></title>

<link rel="stylesheet" type="text/css" href="../css/body.css" />
<link rel="stylesheet" type="text/css" href="../css/start.css" />
<link rel="stylesheet" type="text/css" href="../css/finish.css" />
<link rel="stylesheet" type="text/css" href="../css/key.css" />

<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>

<?php
  $css = '';
  if ($userObject->is_special_needs() and $bgcolor != '#FFFFFF' and $bgcolor != 'white') {
    $css .= "select,input{background-color:$bgcolor;color:$fgcolor;font-family:$font,sans-serif}\n";
    $css .= ".key{background-color:$bgcolor}\n";
  }
  if (($bgcolor != '#FFFFFF' and $bgcolor != 'white') or ($fgcolor != '#000000' and $fgcolor != 'black') or $textsize != 90) {
    $css .= "body {background-color:$bgcolor;color:$fgcolor;font-size:$textsize%}\n";
    $css .= ".staffview {\nbackground: -moz-linear-gradient(top, #FF8282, $bgcolor);\nbackground: -webkit-linear-gradient(top, #FF8282, $bgcolor);\nbackground-image: -ms-linear-gradient(top, #FF8282 0%, $bgcolor 100%);\nfilter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FF8282', endColorstr='$bgcolor');\n}\n";
  }
  if ($font != 'Arial') {
    if (strpos($font,' ') === false) {
      $css .= "body {font-family:$font,sans-serif}\n";
      $css .= "pre {font-family:$font,sans-serif}\n";
    } else {
      $css .= "body {font-family:'$font',sans-serif}\n";
      $css .= "pre {font-family:'$font',sans-serif}\n";
    }
  }
  if ($themecolor != '#316AC5') {
    $css .= ".theme {color:$themecolor}\n";
    $css .= ".objH {color:$themecolor}\n";
  }
  if ($labelcolor != '#316AC5') {
    $css .= ".fback {color:$labelcolor}\n";
    $css .= ".label {color:$labelcolor}\n";
  }
  if ($css != '') {
    echo "<style type=\"text/css\">\n$css</style>\n";
  }

  echo "<script type=\"text/javascript\" src=\"../js/student_help.js\"></script>\n";
  if ($show_feedback) {     // Do not JavaScript files if feedback is not displayed.
    if ($latex_needed == 1) {
      echo "<script type=\"text/javascript\" src=\"../js/jquery-migrate-1.2.1.min.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../tools/mee/mee/js/mee_src.js\"></script>\n";
    }
    if ($configObject->get('cfg_interactive_qs') == 'html5') {
      echo "<script type=\"text/javascript\">\nvar lang_string = " . json_encode($jstring) . "\n</script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/html5.images.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/qsharedf.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/qlabelling.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/qhotspot.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/qarea.js\"></script>\n";
    } else {
      echo "<script type=\"text/javascript\" src=\"../js/ie_fix.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/flash_include.js\"></script>\n";
      echo "<script type=\"text/javascript\" src=\"../js/jquery.flash_q.js\"></script>\n";
    }
  }
  echo $configObject->get('cfg_js_root');
?>
<script>
  window.history.go(1);
	
	$(document).ready(function () {
    
    $('.raw_textarea').each(function() {
      var boxWidth = $(this).width();
      var boxHeight = $(this).height();
      
      var targetID = 'div_' + $(this).attr('id');
      
      $('#' + targetID).width(boxWidth);
      $('#' + targetID).height(boxHeight);
    });
    
    $('#close').click(function() {
      window.close();
    });
    
	});
</script>
</head>
<body>
<?php
  $preview_q_id = (isset($_GET['q_id'])) ? $_GET['q_id'] : null;

  if (isset($_POST['current_screen'])) {
    $current_screen = $_POST['current_screen'];
  } else {
    $current_screen = 1;
  }
  if ($current_screen > 1 and (!isset($_GET['dont_record']) or $_GET['dont_record'] != true)) {
    // Record answers from the previous screen.
    record_marks($paperID, $mysqli, $paper_type, $metadataid, $preview_q_id);
  }

  if (isset($_GET['userID'])) {
    $temp_userID = $_GET['userID'];
    $result = $mysqli->prepare("SELECT title, initials, surname, student_id FROM users LEFT JOIN sid ON users.id = sid.userID WHERE id = ? LIMIT 1");
    $result->bind_param('i', $temp_userID);
    $result->execute();
    $result->store_result();
    $result->bind_result($tmp_title, $tmp_initials, $tmp_surname, $tmp_student_id);
    $result->fetch();
    $result->close();
  } else {
    $temp_userID    = $userObject->get_user_ID();
    $tmp_title      = $userObject->get_title();
    $tmp_initials   = $userObject->get_initials();
    $tmp_surname    = $userObject->get_surname();
    $tmp_student_id = '';
  }
  $old_q_id = 0;
  $old_screen = 0;

  if (!isset($_GET['q_id'])) {
    echo $top_table_html;
    echo '<tr><td><div class="paper">' . $paper_title . '</div>';
    if ($userObject->has_role('External Examiner')) {
      echo '<span style="margin-left:5px; font-size:90%; color:white; font-weight:bold">' . $string['student'] . ' ' . $tmp_student_id . '</span>';
    } elseif ($paper_type < 2 or $userObject->has_role(array('Staff', 'Admin', 'SysAdmin', 'External Examiner'))) {
      echo '<span style="margin-left:5px; font-size:90%; color:white; font-weight:bold">' . $string['answersscreen'];
      $tmp_student_name = $tmp_title . ' ' . demo_replace($tmp_surname, $demo) . ', ' . demo_replace($tmp_initials, $demo);
      $tmp_student_id = demo_replace_number($tmp_student_id, $demo);
      echo ' ' . $tmp_student_name;
      if ($tmp_student_id != '') {
        echo " ($tmp_student_id)";
      }
      echo '</span>';
    }
    echo '</td>';
    echo $logo_html;
    echo '</table>';
  }

  // Get any marking override for the paper
  $overrides = array();
  $sql = "SELECT m.q_id, title, surname, date_marked, new_mark_type, adjmark
          FROM marking_override m INNER JOIN users u ON m.marker_id = u.id
          INNER JOIN log{$log_type} l ON m.log_id = l.id
          WHERE user_id = ? AND paper_id = ?";
  $result = $mysqli->prepare($sql);
  $result->bind_param('ii', $temp_userID, $paperID);
  $result->execute();
  $result->store_result();
  $result->bind_result($o_q_id, $o_title, $o_surname, $o_date_marked, $o_new_mark_type, $o_adjmark);
  while($result->fetch()) {
    $overrides[$o_q_id] = array('q_id' => $o_q_id, 'title' => $o_title, 'surname' => $o_surname, 'date_marked' => $o_date_marked, 'new_mark_type' => $o_new_mark_type, 'adjmark' => $o_adjmark);
  }
  $result->close();
  
  $status_array = QuestionStatus::get_all_statuses($mysqli, $string, true);
  if ($show_feedback) {
    display_feedback($propertyObj, $temp_userID, $log_type, $userObject, $log_metadata, $mysqli, $status_array, $overrides, $preview_q_id);

    // Record the fact that the script has been viewed.
    $logger = new Logger($mysqli);
    if ($userObject->has_role(array('SysAdmin','Admin','Staff','External Examiner'))) {
      $logger->record_access($userObject->get_user_ID(), 'Assessment script', '/paper/finish.php?' . $_SERVER['QUERY_STRING']);    // Staff write in the URL details
    } else {
      $logger->record_access($userObject->get_user_ID(), 'Assessment script', $paperID);  // Students write in the paperID
    }
  } else {
    echo '<blockquote>';
    echo '<div class="thankyou">' . $string['thankyou'] . '</div>';
    echo '<p>' . sprintf($string['msg1'], $paper_title) . '</p><br />';
    if ($paper_postscript != '') echo "<p>$paper_postscript</p>\n";
    echo '</blockquote>';
    if ($paper_type == '2') {
      echo '<br /><div class="key" style="text-align:center">' . $leaving_rules . '<br /><br /><input type="button" name="close" id="close" value="' . $string['closewindow'] . '" class="ok" /></div>';
    } else {
      echo '<br /><div align="center"><input type="button" name="close" id="close" value="' . $string['closewindow'] . '" class="ok" /></div>';
    }
  }
  echo "</body>\n</html>";
  $mysqli->close();
?>

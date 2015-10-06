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
* @copyright Copyright (c) 2013 The University of Nottingham
* @package
*/

require '../include/staff_auth.inc';
require '../include/question_types.inc';
require '../include/display_functions.inc';
require '../include/media.inc';

$marks_color = '#808080';
$themecolor = '#316AC5';
$labelcolor = '#C00000';
$textsize = 100;
$question_no = 0;

$old_q_id = '';
$question_data = $mysqli->prepare("SELECT q_type, q_id, score_method, display_method, settings, marks_correct, marks_incorrect, marks_partial, theme, scenario, leadin, correct, REPLACE(option_text,'\t','') AS option_text, q_media, q_media_width, q_media_height, o_media, o_media_width, o_media_height, notes, settings FROM questions LEFT JOIN options on questions.q_id=options.o_id  WHERE q_id=?  ORDER BY id_num");
$question_data->bind_param('i', $_GET['q_id']);
$question_data->execute();
$question_data->store_result();
$question_data->bind_result($q_type, $q_id, $score_method, $display_method, $settings, $marks_correct, $marks_incorrect, $marks_partial, $theme, $scenario, $leadin, $correct, $option_text, $q_media, $q_media_width, $q_media_height, $o_media, $o_media_width, $o_media_height, $notes, $settings);
$num_rows = $question_data->num_rows;
while ($question_data->fetch()) {
  if ($old_q_id != $q_id) {

    $question['theme'] = trim($theme);
    $question['scenario'] = trim($scenario);
    $question['leadin'] = trim($leadin);
    $question['notes'] = trim($notes);
    $question['q_type'] = $q_type;
    $question['q_id'] = $q_id;
    $question['score_method'] = $score_method;
    $question['display_method'] = $display_method;
    $question['settings'] = $settings;
    $question['q_media'] = $q_media;
    $question['q_media_width'] = $q_media_width;
    $question['q_media_height'] = $q_media_height;
    $question['dismiss'] = '';
    $question['settings'] = $settings;
    if ($q_type == 'enhancedcalc') {
      if (!is_array($settings)) {
        $settings = json_decode($settings, true);
      }
      if (!isset($question['object'])) {
        require_once '../plugins/questions/enhancedcalc/enhancedcalc.class.php';
        $question['object'] = new EnhancedCalc($configObject);
        $question['object']->load($question);
      }
    }
  }
  $question['options'][] = array('correct'=>$correct, 'option_text'=>$option_text, 'o_media'=>$o_media, 'o_media_width'=>$o_media_width, 'o_media_height'=>$o_media_height, 'marks_correct'=>$marks_correct, 'marks_incorrect'=>$marks_incorrect, 'marks_partial'=>$marks_partial);
}
$question_data->close();

$question_no = 0;
$paper_type = 0;
$unanswered = false;
$user_answers[1] = array();

$question['assigned_number'] = (isset($_GET['qNo'])) ? $_GET['qNo'] : 1;
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

  <title><?php echo $string['preview'] ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/start.css" />

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="../js/start.js"></script>
  <script type="text/javascript" src="../js/flash_include.js"></script>
  <script type="text/javascript" src="../tools/mee/mee/js/mee_src.js"></script>
  
  <?php
  if ($question['q_type'] == 'hotspot' or $question['q_type'] == 'labelling' or $question['q_type'] == 'area') {
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
</head>
<body>
<div id="maincontent">
  <table cellpadding="4" cellspacing="0" border="0" width="100%" style="table-layout:fixed">
  <col width="40"><col>
<?php
  display_question($configObject, $question, $paper_type, 0, 1, '', $question_no, $user_answers, $unanswered);

  $question_nos[] = $old_q_id;
  echo "<table>\n";
 ?>
 </div>
 </body>
 </html>

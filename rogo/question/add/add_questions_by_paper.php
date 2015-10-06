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

require '../../include/staff_auth.inc';
require '../../include/errors.inc';
require '../../include/question_types.inc';
require_once '../../classes/questionutils.class.php';
require_once '../../classes/question_status.class.php';

$question_paper = check_var('question_paper', 'GET', true, false, true);

if (!Paper_utils::paper_exists($question_paper, $mysqli)) {
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../../artwork/page_not_found.png', '#C00000', true, true);
}

// Get question statuses
$status_array = QuestionStatus::get_all_statuses($mysqli, $string, true);
?>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

  <title>by Paper</title>

  <link rel="stylesheet" type="text/css" href="../../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../../css/header.css" />
  <style type="text/css">
    body {font-size:80%}
    .divider {font-size:80%; font-weight:bold; padding-left:6px}
    .s {padding-left:6px}
    .q_no {text-align:right; width:35px}
    .mee { display: inline; }

<?php echo QuestionStatus::generate_status_css($status_array); ?>
  </style>

  <script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../../js/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="../../tools/mee/mee/js/mee_src.js"></script>
  <script>
    function Qpreview(qID) {
      parent.previewurl.location = '../view_question.php?q_id=' + qID;
    }

    function populateTicks() {
      q_array = parent.top.controls.document.getElementById('questions_to_add').value.split(",");
      for (i=0; i<q_array.length; i++) {
        if (q_array[i]!='') {
          var obj = document.getElementById(q_array[i]);
          if (obj != null) {
            obj.checked = true;
          }
        }
      }
    }
  </script>
</head>

<body onload="populateTicks()">
<?php
  // Get the title of the paper.
  $stmt = $mysqli->prepare("SELECT paper_title FROM properties WHERE property_id = ?");
  $stmt->bind_param('i', $question_paper);
  $stmt->execute();
  $stmt->bind_result($paper_title);
  $stmt->fetch();
  $stmt->close();

  echo "<form name=\"theform\" method=\"post\" action=\"\">\n";
  echo "<input type=\"hidden\" name=\"screen\" value=\"1\" />\n";
  echo "<table class=\"header\">\n";
  echo "<tr><th colspan=\"7\" style=\"font-size:160%; font-weight:bold\">&nbsp;$paper_title</th></tr>\n";
  echo "<tr><th></th><th></th><th>&nbsp;</th><th class=\"vert_div\">" . $string['question'] . "</th><th class=\"vert_div\">" . $string['type'] . "</th><th style=\"width:90px\" class=\"vert_div\">" . $string['modified'] . "</th><th class=\"vert_div\" style=\"width:90px\">" . $string['status'] . "</th></tr>\n";

  // Get the questions in order off the paper.
  $stmt = $mysqli->prepare("SELECT questions.q_id, leadin, q_type, screen, DATE_FORMAT(last_edited,' {$configObject->get('cfg_short_date')}') AS last_edited, locked, parts, status FROM (papers, questions) LEFT JOIN question_exclude ON questions.q_id = question_exclude.q_id WHERE papers.paper = ? AND papers.question = questions.q_id ORDER BY screen, display_pos");
  $stmt->bind_param('i', $question_paper);
  $stmt->execute();
  $stmt->bind_result($q_id, $leadin, $q_type, $screen, $last_edited, $locked, $parts, $status);
  $old_screen = 0;
  $question_no = 0;
  while ($stmt->fetch()) {
    if ($q_type != 'info') $question_no++;
    if ($screen > $old_screen) {
      echo '<tr><td colspan="7" style="height:10px"></td></tr>';
      echo '<tr><td colspan="7"><table border="0" style="padding-left:10px; padding-right:2px; padding-bottom:5px; width:100%; color:#1E3287"><tr><td><nobr>' . $string['screen'] . ' ' . $screen . '</nobr></td><td style="width:98%"><hr noshade="noshade" style="border:0px; height:1px; color:#E5E5E5; background-color:#E5E5E5; width:100%" /></td></tr></table></td></tr>';
    }
    if ($q_type == 'info') {
      echo "<tr><td class=\"q_no\"><img src=\"../../artwork/black_white_info_icon.png\" width=\"6\" height=\"12\" alt=\"Info\" />&nbsp;</td><td>";
    } else {
    $status_class = 'status' . $status;
    echo "<tr class=\"{$status_class}\"><td class=\"q_no\">$question_no.</td><td>";
    }
    if ($locked != '') echo '<img src="../../artwork/small_padlock.png" width="18" height="18" alt="Locked" />';
    echo "</td><td style=\"width:25px\"><input onclick=\"parent.top.controls.checkStatus(this)\" type=\"checkbox\" name=\"$q_id\" id=\"$q_id\" value=\"$q_id\" /></td>";
    if ($parts == '') {
      echo '<td onclick="Qpreview(' . $q_id . ')">';
    } else {
      echo '<td style="color:red; text-decoration:line-through" onclick="Qpreview(' . $q_id . ')">';
    }
    $leadin = QuestionUtils::clean_leadin($leadin);
    echo $leadin . "</td><td class=\"s\"><nobr>" . fullQuestionType($q_type, $string) . "</nobr></td><td class=\"s\">$last_edited</td><td>" . $status_array[$status]->get_name() . "</td></tr>\n";
    $old_screen = $screen;
  }
  $stmt->close();

?>
</table>
</form>
</body>
</html>
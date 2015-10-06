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

require '../include/staff_auth.inc';
require_once '../include/errors.inc';
require_once '../classes/folderutils.class.php';
require_once '../classes/paperproperties.class.php';

$paperID		= check_var('paperID', 'GET', true, false, true);
$startdate	= check_var('startdate', 'GET', true, false, true);
$enddate		= check_var('enddate', 'GET', true, false, true);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

  <title><?php echo $string['qualitativeanalysis'] . " " . $configObject->get('cfg_install_type') ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/qualitative.css" />

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery.qualitative.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    var commentsStringMatches = '<?php echo $string['occurencesof'] ?>';
    var commentsString = '<?php echo $string['comments'] ?>';
  </script>
</head>

<body>
<?php
  require '../include/toprightmenu.inc';
	echo draw_toprightmenu();

  $properties = PaperProperties::get_paper_properties_by_id($paperID, $mysqli, $string);

  echo "<form name=\"analyse\" method=\"get\" action=\"" . $_SERVER['PHP_SELF'] . "\">";
  echo "<table class=\"header\" style=\"font-size:90%\">\n";
  echo "<tr><th style=\"width:75%; vertical-align: top\">";
  echo '<div class="breadcrumb"><a href="../index.php">' . $string['home'] . '</a>';
  if (isset($_GET['folder']) and $_GET['folder'] != '') {
    echo '<img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../folder/index.php?folder=' . $_GET['folder'] . '">' . folder_utils::get_folder_name($_GET['folder'], $mysqli) . '</a>';
  } elseif (isset($_GET['module']) and $_GET['module'] != '') {
    echo '<img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../module/index.php?module=' . $_GET['module'] . '">' . module_utils::get_moduleid_from_id($_GET['module'], $mysqli) . '</a>';
  }
  echo '<img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../paper/details.php?paperID=' . $paperID . '">' . $properties->get_paper_title() . '</a></div>';
  echo '<div class="page_title">' . $string['qualitativeanalysis'] . '</div></td>';
  echo '<th valign="top" style="width:25%">';
  echo "<div><img src=\"../artwork/toprightmenu.gif\" id=\"toprightmenu_icon\" /></div>\n";
  echo "<input type=\"text\" name=\"keywords\" id=\"keywords\" size=\"20\" value=\"";
  if (isset($_GET['keywords'])) echo $_GET['keywords'];
  echo "\" /><input type=\"button\" id=\"highlight\" value=\"" . $string['highlight'] . "\" />";
  echo "<br /><input type=\"checkbox\" name=\"collapse\" id=\"collapse\" value=\"1\" />&nbsp;<label for =\"collapse\">" . $string['collapse'] . "</label>";
  echo '&nbsp;&nbsp;&nbsp;&nbsp;';
  echo "<br /><input type=\"checkbox\" name=\"casesensitive\" id=\"casesensitive\" value=\"1\" />&nbsp;<label for =\"casesensitive\">" . $string['casesensitive'] . "</label>";
	$module = (isset($_GET['module']) ? $_GET['module'] : '');
	$folder = (isset($_GET['folder']) ? $_GET['folder'] : '');

  echo '<input type="hidden" name="paperID" value="' . $_GET['paperID'] . '" />';
  echo '<input type="hidden" name="startdate" value="' . $_GET['startdate'] . '" />';
  echo '<input type="hidden" name="enddate" value="' . $_GET['enddate'] . '" />';
  echo '<input type="hidden" name="module" value="' . $module . '" />';
  echo '<input type="hidden" name="folder" value="' . $folder . '" />';
  echo '<input type="hidden" name="repcourse" value="' . $_GET['repcourse'] . '" />';
  echo '<input type="hidden" name="repyear" value="' . $_GET['repyear'] . '" />';
  echo "</th></tr>";
?>
</table>
</form>

<div id="main">
<?php
  $result = $mysqli->prepare("SELECT question FROM papers, questions WHERE papers.question = questions.q_id AND q_type != 'info' AND paper = ? ORDER BY screen, display_pos");
  $result->bind_param('i', $_GET['paperID']);
  $result->execute();
  $result->bind_result($question);
  while ($result->fetch()) {
    $paper_structure[] = $question;
  }
  $result->close();

  $occurrence_comments = 0;
  $old_leadin = '';
  $old_theme = '';
  $old_screen = 1;
  $old_q_id = 0;
  $comment_flag = 1;
  $list_on = 0;
  $q_no = 0;

  $sql = <<< SQL
SELECT DISTINCT l.screen, q.theme, lm.started, u.username, u.surname, l.q_id, q.leadin, l.user_answer
FROM log3 l INNER JOIN log_metadata lm ON l.metadataID = lm.id
INNER JOIN papers p ON p.question = l.q_id AND p.screen = l.screen AND p.paper = lm.paperID
INNER JOIN questions q ON l.q_id = q.q_id
INNER JOIN users u ON lm.userID = u.id
WHERE p.paper = ?
AND lm.student_grade LIKE ?
AND lm.year LIKE ?
AND q.q_type = 'textbox'
AND lm.started >= ? AND lm.started <= ?
AND (u.roles = 'Student' OR u.roles = 'graduate')
ORDER BY l.screen, p.display_pos
SQL;

  $result = $mysqli->prepare($sql);
  $result->bind_param('issss', $_GET['paperID'], $_GET['repcourse'], $_GET['repyear'], $startdate, $enddate);
  $result->execute();
  $result->bind_result($screen, $theme, $started, $tmp_username, $surname, $q_id, $leadin, $user_answer);

  while ($result->fetch()) {
    if ($theme != '') $old_theme = $theme;
    if ($old_q_id != $q_id or $old_screen < $screen) {
      if ($comment_flag == 0) echo "<div class=\"comments\">" . $string['nocomments'] . "</div>\n";
      if ($old_q_id != 0) {
        if ($list_on == 1) echo "</ul>\n";
        $list_on = 0;
        echo "<div class=\"comments\">" . sprintf($string['comments'], $occurrence_comments) . "</div>\n";
      }
      $comment_flag = 0;
      if ($old_screen < $screen) {
        if ($list_on == 1) echo "</ul>\n";
        $list_on = 0;
        echo '<br /><div class="screenbrk"><span class="scr_no">' . $string['screen'] . '&nbsp;' . $screen . '</span></div>';
      }

      if ($old_theme != '') {
        echo "<h1>$old_theme</h1>\n";
      }
      do {
        $q_no++;
      } while ($q_id != $paper_structure[$q_no-1] and $q_no < 9999);
      if ($list_on == 1) echo "</ul>\n";
      echo "<p style=\"font-weight:bold; margin-left:10px; margin-right:10px\">$q_no. $leadin</p>\n<ul class=\"response-list\">\n";
      $occurrence_comments = 0;
      $list_on = 1;
    }
    $response = trim(strtolower($user_answer));
    // $match = false;
    if ($response != NULL and $response != 'n/a' and strlen($response) > 1) {
      $occurrence_comments++;
      echo "<li class=\"response\">$user_answer</li>\n";
      $comment_flag = 1;
    }
    $old_leadin = $leadin;
    $old_screen = $screen;
    $old_q_id = $q_id;
  }
  $result->close();
  echo "</ul>\n";

  if ($comment_flag == 0) {
    echo "<div class=\"comments\">" . $string['nocomments'] . "</div>\n";
  } else {
    echo "<div class=\"comments\">" . sprintf($string['comments'], $occurrence_comments) . "</div>\n";
  }
  $mysqli->close();
?>
</div>
</body>
</html>
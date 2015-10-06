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
require_once '../include/errors.inc';
require_once '../classes/courseutils.class.php';
require_once '../classes/logger.class.php';

$courseID = check_var('courseID', 'REQUEST', true, false, true);

if (!CourseUtils::courseid_exists($courseID, $mysqli)) {
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
}

$unique_course = true;
$tmp_course = '';

$result = $mysqli->prepare("SELECT schoolid, name, description FROM courses WHERE id = ? LIMIT 1");
$result->bind_param('i', $courseID);
$result->execute();
$result->bind_result($current_school, $name, $description);
$result->fetch();
$result->close();
  
if (isset($_POST['submit']) and $_POST['course'] != $_POST['old_course']) {
  // Check for unique course name
  $new_course = trim($_POST['course']);
  $course_exists = CourseUtils::course_exists($new_course, $mysqli);
}

if (isset($_POST['submit']) and $course_exists == false) {
  $new_course = trim($_POST['course']);
  $new_school = $_POST['school'];
  $new_description = trim($_POST['description']);

  $result = $mysqli->prepare("UPDATE courses SET name = ?, description = ?, schoolid = ? WHERE id = ?");
  $result->bind_param('ssii', $new_course, $new_description, $new_school, $courseID);
  $result->execute();  
  $result->close();
  
  $logger = new Logger($mysqli);
  if ($name != $new_course)             $logger->track_change('Course', $courseID, $userObject->get_user_ID(), $name, $new_course, 'code');
  if ($description != $new_description) $logger->track_change('Course', $courseID, $userObject->get_user_ID(), $description, $new_description, 'name');
  if ($current_school != $new_school)   $logger->track_change('Course', $courseID, $userObject->get_user_ID(), $current_school, $new_school, 'school');
  
  
  $mysqli->close();
  header("location: list_courses.php");
  exit();
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>Edit Course</title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <style type="text/css">
    .field {font-weight:bold; text-align:right; padding-right:10px}
  </style>

  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    $(function () {
      $('#theform').validate({
        errorClass: 'errfield',
        errorPlacement: function(error,element) {
          return true;
        }
      });
      
      $('form').removeAttr('novalidate');
      
      $('#cancel').click(function() {
        history.back();
      });
    });
    
    function codeWarning() {
      alert("<?php echo sprintf($string['coursecodeinuse'], $tmp_course); ?>");
    }
  </script>
</head>
<?php
  if ($unique_course == false) {
    echo "<body onload=\"codeWarning()\">\n";
  } else {
    echo "<body>\n";
  }
  require '../include/course_options.inc';
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
  ?>
  <div id="content">
  <div class="head_title">
    <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
    <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" /><a href="list_courses.php"><?php echo $string['courses'] ?></a></div>
    <div class="page_title"><?php echo $string['editcourse']; ?></div>
  </div>
  <br />
  <div align="center">
  <form id="theform" name="edit_course" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?courseID=' . $courseID ?>">
    <table cellpadding="0" cellspacing="2" border="0" style="text-align:left">
    <?php
    if ($unique_course == false) {
      echo "<tr><td class=\"field\">" . $string['code'] . "</td><td><input type=\"text\" size=\"10\" maxlength=\"255\" name=\"course\" style=\"background-color:#FFD9D9; color:#800000; border:1px solid #800000\" value=\"$tmp_course\" required /><input type=\"hidden\" name=\"old_course\" value=\"$tmp_course\" /></td></tr>\n";
    } else {
      echo "<tr><td class=\"field\">" . $string['code'] . "</td><td><input type=\"text\" size=\"10\" maxlength=\"255\" name=\"course\" value=\"" . $name . "\" /><input type=\"hidden\" name=\"old_course\" value=\"$name\" required /></td></tr>\n";
    }
    ?>
    <tr><td class="field"><?php echo $string['name']; ?></td><td><input type="text" size="70" maxlength="255" name="description" value="<?php echo $description; ?>" required /></td></tr>
    <tr><td class="field"><?php echo $string['school']; ?></td><td><select name="school" required>
    <?php
      $result = $mysqli->prepare("SELECT schools.id, school, name FROM schools, faculty WHERE schools.facultyID = faculty.id AND school != '' ORDER BY name, school");
      $result->execute();
      $result->bind_result($schoolid, $school, $faculty);
      
      $old_faculty = '';
      while ($result->fetch()) {
        if ($faculty != $old_faculty) {
          if ($old_faculty != '') echo "</optgroup>\n";
          echo "<optgroup label=\"$faculty\">\n";
        }
        if ($current_school == $schoolid) {
          echo "<option value=\"$schoolid\" selected>$school</option>\n";
        } else {
          echo "<option value=\"$schoolid\">$school</option>\n";
        }
        $old_faculty = $faculty;
      }
      echo "</optgroup>\n";
      $result->close();
      
    ?>
    </select></td></tr>
    </table>
    <input type="hidden" name="courseID" value="<?php echo $courseID; ?>" />
    <p><input type="submit" class="ok" name="submit" value="<?php echo $string['save'] ?>"><input type="button" class="cancel" name="home" id="cancel" value="<?php echo $string['cancel'] ?>" /></p>
  </form>
  </div>
<?php
}
$mysqli->close();
?>
</div>
</body>
</html>
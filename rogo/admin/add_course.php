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

$unique_course = true;
if (isset($_POST['submit'])) {
  // Check for unique username
  $tmp_course = trim($_POST['course']);
  
  if (CourseUtils::course_exists($tmp_course, $mysqli)) {
    $unique_course = false;
  } else {
    $unique_course = true;
  }
}

if (isset($_POST['submit']) and $unique_course == true) {
  $tmp_school = $_POST['school'];
  $tmp_course = trim($_POST['course']);
  $tmp_description = trim($_POST['description']);

  $result = $mysqli->prepare("INSERT INTO courses VALUES (NULL, ?, ?, NULL, ?)");
  $result->bind_param('ssi', $tmp_course, $tmp_description, $tmp_school);
  $result->execute();
  $result->close();
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
  <title><?php echo $string['createnewcourse']; ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <style type="text/css">
    .field {font-weight:bold; text-align:right; padding-right:10px}
    .warn {background-color:#FFD9D9; color:#800000; border:1px solid #800000}
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
  </script>
  </head>

  <body>
  <?php
    require '../include/course_options.inc';
    require '../include/toprightmenu.inc';

    echo draw_toprightmenu();
  ?>
  <div id="content">
  <div class="head_title">
    <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
    <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" /><a href="list_courses.php"><?php echo $string['courses'] ?></a></div>
    <div class="page_title"><?php echo $string['createnewcourse']; ?></div>
  </div>
  <br />
  <div align="center">
  <form id="theform" name="edit_course" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table cellpadding="0" cellspacing="2" border="0" style="text-align:left">
    <?php
    if ($unique_course == false) {
      echo "<tr><td class=\"field\">" . $string['code'] . "</td><td><input type=\"text\" size=\"10\" maxlength=\"255\" name=\"course\" class=\"warn\" value=\"$tmp_course\" required /></td></tr>\n";
    } else {
    ?>
      <tr><td class="field"><?php echo $string['code'] ?></td><td><input type="text" size="10" maxlength="255"  name="course" value="<?php if (isset($_GET['moduleid'])) echo $_GET['moduleid']; ?>" required /></td></tr>
    <?php
    }
    ?>
    <tr><td class="field"><?php echo $string['name'] ?></td><td><input type="text" size="70" maxlength="255" name="description" value="<?php if (isset($_POST['description'])) echo $_POST['description']; ?>" required /></td></tr>
    <tr><td class="field"><?php echo $string['school'] ?></td><td><select name="school" required>
    <option value=""></option>
    <?php
      $result = $mysqli->prepare("SELECT schools.id, school, name FROM schools, faculty WHERE schools.facultyID = faculty.id AND schools.deleted IS NULL ORDER BY name, school");
      $result->execute();
      $result->bind_result($schoolid, $school, $faculty);

      $old_faculty = '';
      while ($result->fetch()) {
        if ($faculty != $old_faculty) {
          if ($old_faculty != '') echo "</optgroup>\n";
          echo "<optgroup label=\"$faculty\">\n";
        }
        if (isset($_POST['schoolid']) and $_POST['schoolid'] == $school) {
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
    <p><input type="submit" class="ok" name="submit" value="<?php echo $string['add'] ?>"><input class="cancel" id="cancel" type="button" name="home" value="<?php echo $string['cancel'] ?>" /></p>
  </form>
  </div>
<?php
}
$mysqli->close();
?>
</div>

</body>
</html>
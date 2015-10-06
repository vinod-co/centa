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
require_once '../classes/logger.class.php';

$schoolid = check_var('schoolid', 'GET', true, false, true);

$school = $string['prompt'];
$faculty = '';

$result = $mysqli->prepare("SELECT school, facultyID FROM schools WHERE id = ? AND deleted IS NULL");
$result->bind_param('i', $schoolid);
$result->execute();
$result->store_result();
$result->bind_result($school, $curr_faculty);
$result->fetch();
if ($result->num_rows == 0) {
  $result->close();
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
}
$result->close();

if (isset($_POST['submit'])) {
  $school_tmp = trim( $_POST['school']);
  $faculty = trim( $_POST['faculty']);

  $changed = ($curr_faculty != $faculty or $school != $school_tmp);

  if ($changed and SchoolUtils::school_exists_in_faculty($faculty, $school_tmp, $mysqli)) {
    $error = 'duplicate';
    $school = $school_tmp;
    $curr_faculty = $faculty;
  } else {
    if ($changed) {
      $result = $mysqli->prepare("UPDATE schools SET facultyID = ?, school = ? WHERE id = ?");
      $result->bind_param('isi', $faculty, $school_tmp, $schoolid);
      $result->execute();
      $result->close();
      
      $logger = new Logger($mysqli);
      if ($school != $school_tmp)     $logger->track_change('School', $schoolid, $userObject->get_user_ID(), $school, $school_tmp, $string['name']);
      if ($curr_faculty != $faculty)  $logger->track_change('School', $schoolid, $userObject->get_user_ID(), $curr_faculty, $faculty, $string['faculty']);
    }

    header("location: list_schools.php");
    exit();
  }
}

$faculties = 0;
$faculty_list = array();
$result = $mysqli->prepare("SELECT id, name FROM faculty WHERE deleted IS NULL ORDER BY name");
$result->execute();
$result->bind_result($facultyID, $name);
while ($result->fetch()) {
  $faculty_list[] = array($facultyID, $name);
  $faculties++;
}
$result->close();
?>
<!DOCTYPE html>
  <html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>Rog&#333;: <?php echo $string['editschool'] . " " . $configObject->get('cfg_install_type') ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <style type="text/css">
    td {text-align:left}
    .field {text-align:right; padding-right:10px}
    .form-error {
      width: 468px;
      margin: 18px auto;
      padding: 16px;
      background-color: #FFD9D9;
      color: #800000;
      border: 2px solid #800000;
    }
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
  require '../include/school_options.inc';
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>
<div id="content">

<div class="head_title">
  <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="list_schools.php"><?php echo $string['schools'] ?></a></div>
  <div class="page_title"><?php echo $string['editschool'] ?></div>
</div>

  <br />
  <div align="center">
  <form id="theform" name="add_school" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?schoolid=' . $schoolid ?>">
<?php
  if (isset($error) and $error = 'duplicate') {
?>
    <div class="form-error"><?php echo $string['duplicateerror'] ?></div>
<?php
  }
?>
    <table cellpadding="0" cellspacing="2" border="0">
    <tr><td class="field"><?php echo $string['name'] ?></td><td><input type="text" size="70" maxlength="255" id="school" name="school" value="<?php echo $school ?>" required /></td></tr>
    <tr><td class="field"><?php echo $string['faculty'] ?></td><td><select name="faculty">
    <?php
      foreach ($faculty_list as $faculty) {
        $sel = ($faculty[0] == $curr_faculty) ? ' selected="selected"' : '';
        echo "<option value=\"{$faculty[0]}\"{$sel}>{$faculty[1]}</option>\n";
      }
    ?>
    </select></td></tr>
    </table>
    <p><input type="submit" class="ok" name="submit" value="<?php echo $string['save'] ?>"><input class="cancel" id="cancel" type="button" name="home" value="<?php echo $string['cancel'] ?>" /></p>
  </form>
  </div>
</div>
</body>
</html>
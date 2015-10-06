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
require_once '../classes/schoolutils.class.php';

$school = '';
$faculty = '';

if (isset($_POST['submit'])) {
  $school = trim($_POST['school']);
  $faculty = trim($_POST['facultyID']);

  if (SchoolUtils::school_exists_in_faculty($faculty, $school, $mysqli)) {
    $error = 'duplicate';
  } else {
    $insert_id = SchoolUtils::add_school($faculty, $school, $mysqli);

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
  <title>Rog&#333;: <?php echo $string['addschools'] . ' ' . $configObject->get('cfg_install_type'); ?></title>
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
      border: 2px solid #800000
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
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home']; ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="list_schools.php"><?php echo $string['schools'] ?></a></div>
  <div class="page_title"><?php echo $string['addschools']; ?></div>
</div>

  <br />
  <div align="center">
  <form id="theform" name="add_school" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
  if (isset($error) and $error = 'duplicate') {
?>
    <div class="form-error"><?php echo $string['duplicateerror'] ?></div>
<?php
  }
?>
    <table cellpadding="0" cellspacing="2" border="0">
    <tr><td class="field"><?php echo $string['school']; ?></td><td><input type="text" size="70" maxlength="255" name="school" id="school" value="<?php echo $school ?>" placeholder="<?php echo $string['prompt'] ?>..." required /></td></tr>
    <tr><td class="field"><?php echo $string['faculty']; ?></td><td><select name="facultyID">
    <?php
      foreach ($faculty_list as $faculty) {
        $selected = ($faculty[0] == $faculty) ? ' selected="selected"' : '';
        echo "<option value=\"{$faculty[0]}\"$selected>{$faculty[1]}</option>\n";
      }
    ?>
    </select></td></tr>
    </table>
    <p><input type="submit" class="ok" name="submit" value="<?php echo $string['add'] ?>" /><input class="cancel" id="cancel" type="button" name="home" value="<?php echo $string['cancel'] ?>" /></p>
  </form>
  </div>
</div>
</body>
</html>
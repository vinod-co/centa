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
require '../include/errors.inc';
require '../classes/facultyutils.class.php';
  
$duplicate = false;
if (isset($_POST['ok']) or (isset($_POST['returnhit']) and $_POST['returnhit'] == '1')) {
  $add_faculty = trim($_POST['add_faculty']);
  if ($add_faculty != '') {
    // Check for existing name
    if (FacultyUtils::facultyname_exists($add_faculty, $mysqli)) {
      $duplicate = true;
    } else {
      $duplicate = false;
      FacultyUtils::add_faculty($add_faculty, $mysqli);
    }
  }
  if (!$duplicate) {
?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
<title><?php echo $string['addfaculty']; ?></title>
</head>
<?php
  if ($add_faculty != '') {
    echo "<body onload=\"window.opener.location.href='list_faculties.php'; window.close();\">\n";
  } else {
    echo "<body onload=\"window.close();\">\n";
  }
?>
</body>
</html>
<?php
    exit();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title><?php echo $string['addfaculty']; ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <style type="text/css">
    body {font-size:90%; margin:2px; background-color:#EAEAEA}
    h1 {font-size:140%; font-weight:normal}
  </style>
  
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
  <script>
    $(function () {
      $('#theform').validate({
        errorClass: 'errfield',
        errorPlacement: function(error,element) {
          return true;
        }
      });
      $('form').removeAttr('novalidate');
    });
  </script>
</head>

<body>
<h1><?php echo $string['addfaculty'] ?></h1>
<form id="theform" name="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div><?php
if ($duplicate) {
  echo '<input type="text" style="width:99%; background-color:#FFC0C0; border:solid 1px #C00000; color:#800000" id="add_faculty" name="add_faculty" value="' . $_POST['add_faculty'] . '" maxlength="80" required autofocus />';
  echo "<script language=\"JavaScript\">\nalert('" . $string['facultywarning'] . "');\n</script>\n";
} else {
  echo '<input type="text" style="width:99%" id="add_faculty" name="add_faculty" maxlength="80" required autofocus />';
}
?>
</div>
<div align="right"><input type="submit" name="ok" value="<?php echo $string['ok'] ?>" class="ok" /><input type="button" name="cancel" value="<?php echo $string['cancel'] ?>" class="cancel" style="margin-right:0" onclick="window.close();" /><input type="hidden" name="returnhit" value="" /><input type="hidden" name="module" value="<?php if (isset($_GET['module'])) echo $_GET['module']; ?>" /></div>
</form>

</body>
</html>
<?php
$mysqli->close();
?>
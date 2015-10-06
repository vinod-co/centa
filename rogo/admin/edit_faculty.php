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
require_once '../classes/logger.class.php';
require_once '../classes/facultyutils.class.php';

$facultyID = check_var('facultyID', 'REQUEST', true, false, true);

// Check the Faculty ID actually exists for editing.
$name = FacultyUtils::faculty_name_by_id($facultyID, $mysqli);
if (!$name) {
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
}

$duplicate = false;
if (isset($_POST['submit'])) {
  // Check for existing name
  
  if (FacultyUtils::facultyname_exists($_POST['new_faculty'], $mysqli)) {
    $duplicate = true;
  }
          
  if (!$duplicate) {
    $result = $mysqli->prepare("UPDATE faculty SET name = ? WHERE id = ?");
    $result->bind_param('si', $_POST['new_faculty'], $facultyID);
    $result->execute();
    $result->close();
    
    $logger = new Logger($mysqli);
    if ($name != $_POST['new_faculty']) $logger->track_change('Faculty', $facultyID, $userObject->get_user_ID(), $name, $_POST['new_faculty'], 'name');

  ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title><?php echo $string['editfaculty'] ?></title>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script>
    $(function () {
      window.opener.location.href='list_faculties.php';
      window.close();
    });
  </script>
</head>
<body>
</body>
</html>
  <?php
    exit;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title><?php echo $string['editfaculty']; ?></title>
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
<h1><?php echo $string['editfaculty']; ?></h1>
<form id="theform" name="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div>
<?php
if ($duplicate) {
  echo '<input type="text" style="width:99%; background-color:#FFC0C0; border:solid 1px #C00000; color:#800000" name="new_faculty" value="' . $_POST['new_faculty'] . '" maxlength="80" required autofocus />';
  echo "<script>\nalert('" . $string['warning'] . "');\n</script>\n";
} else {
  echo '<input type="text" style="width:99%" name="new_faculty" value="' . $name . '" maxlength="80" required autofocus />';
}
?>
<input type="hidden" name="facultyID" value="<?php echo $facultyID ?>" />
</div>
<div align="right"><input type="submit" name="submit" value="<?php echo $string['ok'] ?>" class="ok" /><input type="button" name="cancel" value="<?php echo $string['cancel'] ?>" class="cancel" style="margin-right:0" onclick="window.close();" /><input type="hidden" name="returnhit" value="" /></div>
</form>

</body>
</html>
<?php
$mysqli->close();
?>
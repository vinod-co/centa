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
require '../include/errors.inc';
require_once '../classes/dateutils.class.php';

if (isset($_POST['ok']) or (isset($_POST['returnhit']) and $_POST['returnhit'] == '1')) {
  $title = trim($_POST['title']);
  $staff_msg = $_POST['staff_msg'];
  $student_msg = $_POST['student_msg'];
  $startdate = $_POST['fyear'] . $_POST['fmonth'] . $_POST['fday'] . $_POST['ftime'] . '00';
  $enddate = $_POST['tyear'] . $_POST['tmonth'] . $_POST['tday'] . $_POST['ttime'] . '00';
  $icon = str_replace('icon', '', $_POST['icon_type']);  // Take the word icon out, store only the number.
  
  $result = $mysqli->prepare("INSERT INTO announcements VALUES (NULL, ?, ?, ?, ?, ?, ?, NULL)");
  $result->bind_param('ssssss', $title, $staff_msg, $student_msg, $icon, $startdate, $enddate);
  $result->execute();  
  $result->close();
  
  $mysqli->close();
  header("location: list_announcements.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html; charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>Rog&#333;: <?php echo $string['addannouncement']; ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <link rel="stylesheet" type="text/css" href="../css/announcement.css" />
  
  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
<?php
// Override this variable with a specific configuration file for announcements.
$cfg_editor_javascript = <<< SCRIPT
{$configObject->get('cfg_js_root')}
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_config_announcements.js"></script>
SCRIPT;

  echo $cfg_editor_javascript;
?>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
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
   
      $('#cancel').click(function() {
        history.back();
      });
      
      $('.icon').click(function() {
        current = $('#icon_type').val();
        $('#' + current).css('border-color', 'white');

        newvalue = $(this).attr('id');
        $('#' + newvalue).css('border-color', '#FFBD69');
        $('#icon_type').val(newvalue)
        
      });
      
    });
  </script>
</head>

<body>
<?php
  require '../include/announcement_options.inc';
  require '../include/toprightmenu.inc';

	echo draw_toprightmenu();
?>
<div id="content">
<div class="head_title">
  <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a></div>
  <div class="page_title"><?php echo $string['addannouncement'] ?></div>
</div>


<form id="theform" name="myform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<table class="tblform">
  <tr>
    <td></td>
    <td>
      <img class="icon" id="icon1" src="../artwork/news_64.png" style="border-color:#FFBD69" />
      <img class="icon" id="icon2" src="../artwork/new_64.png" />
      <img class="icon" id="icon3" src="../artwork/tip_64.png" />
      <img class="icon" id="icon4" src="../artwork/software_64.png" />
      <img class="icon" id="icon5" src="../artwork/exclamation_64.png" />
      <img class="icon" id="icon6" src="../artwork/sync_64.png" />
      <img class="icon" id="icon7" src="../artwork/megaphone_64.png" />
    </td>
  </tr>
  <tr>
    <td class="field"><?php echo $string['Title']; ?></td>
    <td><input type="text" name="title" size="60" maxlength="255" required /></td>
  </tr>
  <tr>
    <td class="field"><?php echo $string['Available from']; ?></td>
    <td><?php echo date_utils::timedate_select('f', date('YmdH00'), false, date('Y'), date('Y')+2, $string); ?></td>
  </tr>
  <tr>
    <td class="field"><?php echo $string['Available to']; ?></td>
    <td><?php echo date_utils::timedate_select('t', date('YmdH00'), false, date('Y'), date('Y')+2, $string); ?></td>
  </tr>
  <tr>
    <td class="field"><?php echo $string['Staff Message']; ?></td>
    <td><textarea class="mceEditor" id="staff_msg" name="staff_msg" style="width:750px; height:180px; margin:0" rows="5" cols="20"></textarea></td>
  </tr>
  <tr>
    <td class="field"><?php echo $string['Student Message']; ?></td>
    <td><textarea class="mceEditor" id="student_msg" name="student_msg" style="width:750px; height:180px; margin:0" rows="5" cols="20"></textarea></td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center; padding-top:10px"><input type="submit" name="ok" value="<?php echo $string['ok'] ?>" class="ok" /><input type="button" name="cancel" id="cancel" value="<?php echo $string['cancel'] ?>" class="cancel" /></td>
  </tr>
</table>

<input type="hidden" id="icon_type" name="icon_type" value="icon1" />

</form>
</div>
</body>
</html>
<?php
$mysqli->close();
?>
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
* @author Simon Wilkinson, Joseph Baxter
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/sysadmin_auth.inc';
require_once '../classes/stateutils.class.php';

$state = $stateutil->getState();

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <title>Rog&#333;: <?php echo $string['systemerrors'] . ' ' . $configObject->get('cfg_install_type'); ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />
  <style type="text/css">
    .errl {padding-right:6px; vertical-align:top; text-align:right}
	</style>

  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/state.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/list.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    function refreshPage() {
      window.location = 'sys_error_list.php';
    }

    function openBug(lineID, event) {
      selLine(lineID, event);
      displayDetails(lineID, event);
    }

    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({
          dateFormat: '<?php echo $configObject->get('cfg_tablesorter_date_time'); ?>',
          sortList: [[0,1]]
        });
      }

      $(".l").click(function(event) {
        event.stopPropagation();
        selLine($(this).attr('id'),event);
      });

      $(".l").dblclick(function(event) {
        event.stopPropagation();
        openBug($(this).attr('id'),event);
      });

      $("#sys_errors_menu").click(function(event) {
          event.stopPropagation();
          displayDetails($('#lineID').val(), event);
      });

    });
  </script>
</head>
<body>
<?php
  require '../include/sys_errors_menu.inc';
  require '../include/toprightmenu.inc';

	echo draw_toprightmenu();

  if (isset($state['showfixed']) and $state['showfixed'] == 'true') {
    $sql = "SELECT fixed, sys_errors.id, title, initials, surname, DATE_FORMAT(occurred,'{$configObject->get('cfg_long_date_time')}'), errtype, errstr, errfile, errline, users.id FROM sys_errors LEFT JOIN users ON users.id = sys_errors.userID ORDER BY sys_errors.id DESC";
  } else {
    $sql = "SELECT fixed, sys_errors.id, title, initials, surname, DATE_FORMAT(occurred,'{$configObject->get('cfg_long_date_time')}'), errtype, errstr, errfile, errline, users.id FROM sys_errors LEFT JOIN users ON users.id = sys_errors.userID WHERE fixed IS NULL ORDER BY sys_errors.id DESC";
  }

  $result = $mysqli->prepare($sql);
  $result->execute();
  $result->store_result();
  $result->bind_result($fixed, $errorID, $title, $initials, $surname, $occurred, $errtype, $errstr, $errfile, $errline, $tmp_userID);
?>
<div id="content">
<table class="header">
<tr>
  <th colspan="4"><div class="breadcrumb"><a href="../index.php"><?php echo $string['home']; ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools']; ?></a></div><div class="page_title"><?php echo $string['systemerrors'] ?> (<?php echo number_format($result->num_rows) ?>)</div></th>
<th colspan="3" style="text-align:right; vertical-align:top"><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /><br /><div style="padding-top:5px"><input class="chk" type="checkbox" name="showfixed" id="showfixed" value="1" onclick="refreshPage();"<?php if (isset($state['showfixed']) and $state['showfixed'] == 'true') echo ' checked="checked"'; ?> /> <?php echo $string['showfixed'] ?>&nbsp;</div></th>
</tr>
</table>
<table class="header" id="maindata">
  <thead>
    <tr><th><div class="col10"><?php echo $string['date'] ?></div></th><th class="vert_div"><?php echo $string['type'] ?></th><th class="vert_div"><?php echo $string['message'] ?></th><th class="vert_div"><?php echo $string['file'] ?></th><th class="vert_div"><?php echo $string['lineno'] ?></th><th class="vert_div"><?php echo $string['user'] ?></th></tr>
</thead>
<tbody>

<?php
  while ($result->fetch()) {
    if ($fixed == '') {
      echo "<tr class=\"l\" id=\"$errorID\"><td><nobr>$occurred<nobr></td><td>$errtype</td><td>$errstr</td><td>$errfile</td><td class=\"errl\">$errline</td><td><nobr>$title $initials $surname</nobr></td></tr>\n";
    } else {
      echo "<tr class=\"l deleted\" id=\"$errorID\"><td><nobr>$occurred</nobr></td><td>$errtype</td><td>$errstr</td><td>$errfile</td><td class=\"errl\">$errline</td><td>";
      if ($surname == '') {
        echo '<span class="grey">unauthenticated</span>';
      } else {
        echo "<nobr>$title $initials $surname</nobr>";
      }
      echo "</td></tr>\n";
    }
  }
?>
</tbody>
</table>

</div>
</body>
</html>

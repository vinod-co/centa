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
  require '../include/sidebar_menu.inc';
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title>Rog&#333;: <?php echo $string['smsimportsummary'] . ' ' . $configObject->get('cfg_install_type'); ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />
  
  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>    
    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({ 
          dateFormat: '<?php echo $configObject->get('cfg_tablesorter_date_time'); ?>',
          sortList: [[0,1]] 
        });
      }

      $(".l").click(function() {
        window.location='sms_import_detail.php?day=' + $(this).attr('id');
      });
    
    });
  </script>
</head>
<body>
<?php
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>

<div id="content">
  
<div class="head_title">
  <img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" />
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools']; ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./list_modules.php"><?php echo $string['modules']; ?></a></div>
  <div class="page_title"><?php echo $string['smsimportsummary'] ?></div>
</div>
  
<table id="maindata" class="header tablesorter" cellspacing="0" cellpadding="2" border="0">
<thead>
  <tr>
    <th><div style="padding-left:10px"><?php echo $string['date'] ?></div></th>
    <th class="vert_div"><?php echo $string['modules'] ?></th>
    <th class="vert_div"><?php echo $string['enroled'] ?></th>
    <th class="vert_div"><?php echo $string['deleted'] ?></th>
    <th style="width:50%">&nbsp;</th>
  </tr>
</thead>

<tbody>
<?php
  $id = 1;
  $result = $mysqli->prepare("SELECT DATE_FORMAT(updated,'%Y%m%d'), DATE_FORMAT(updated,'%d/%m/%Y'), COUNT(id), SUM(enrolements), SUM(deletions) FROM sms_imports GROUP BY updated ORDER BY updated DESC");
  $result->execute();
  $result->store_result();
  $result->bind_result($updated, $display_updated, $module_no, $enrolement_no, $deletion_no);
  while ($result->fetch()) {
    echo "<tr id=\"$updated\" class=\"l\"><td style=\"padding-left:10px\">$display_updated</td><td class=\"no\">$module_no</td><td class=\"no\">$enrolement_no</td><td class=\"no\">$deletion_no</td><td></td></tr>\n";
    $id++;
  }
?>
</tbody>
</table>
</div>

</body>
</html>

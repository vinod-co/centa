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
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title>Rog&#333;: <?php echo $string['newsannouncements'] . ' ' . $configObject->get('cfg_install_type') ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />
 
  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/list.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    function edit(lineID) {
      document.location.href='./edit_announcement.php?announcementid=' + lineID;
    }
    
    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({ 
          dateFormat: '<?php echo $configObject->get('cfg_tablesorter_date_time'); ?>',
          sortList: [[1,1]] 
        });
      }

      $(".l").click(function(event) {
        event.stopPropagation();
        selLine($(this).attr('id'),event);
      });

      $(".l").dblclick(function() {
        edit($(this).attr('id'));
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
  <div class="page_title"><?php echo $string['newsannouncements'] ?></div>
</div>
  
<table id="maindata" class="header tablesorter" cellspacing="0" cellpadding="2" border="0" style="width:100%">
<thead>
<tr>
  <th class="col" style="width:50%"><?php echo $string['title'] ?></th>
  <th class="col" style="width:25%"><?php echo $string['startdate'] ?></th>
  <th class="col" style="width:25%"><?php echo $string['enddate'] ?></th>
</tr>
</thead>

<tbody>
<?php
$announce_no = 0;
$announcements = array();

$result = $mysqli->prepare("SELECT id, title, startdate, DATE_FORMAT(startdate, '" . $configObject->get('cfg_long_date_time') . "') AS startdate_display, DATE_FORMAT(enddate, '" . $configObject->get('cfg_long_date_time') . "') AS enddate_display FROM announcements WHERE deleted IS NULL");
$result->execute();
$result->bind_result($announcementid, $title, $startdate, $startdate_display, $enddate_display);
while ($result->fetch()) {
  $announcements[$announce_no]['announcementid'] = $announcementid;
  $announcements[$announce_no]['title'] = $title;
  $announcements[$announce_no]['startdate_display'] = $startdate_display;
  $announcements[$announce_no]['enddate_display'] = $enddate_display;
  
  $announce_no++;
}
$result->close();

for ($i=0; $i<$announce_no; $i++) {
  echo "<tr id=\"" . $announcements[$i]['announcementid'] . "\" class=\"l\"><td>" . $announcements[$i]['title'] . "</td><td>" . $announcements[$i]['startdate_display']  . "</td><td>" . $announcements[$i]['enddate_display']  . "</td></tr>\n";
}

$mysqli->close();
?>
</tbody>
</table>
</div>

</body>
</html>
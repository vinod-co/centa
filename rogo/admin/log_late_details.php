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
  require_once '../classes/networkutils.class.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
	
  <title>Rog&#333;: <?php echo $string['loglatedetails'] ?></title>
	
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />
  <style type="text/css">
    .icon {padding-left:10px}
  </style>
  
  <?php echo $configObject->get('cfg_js_root') ?>
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({ 
          sortList: [[1,0]] 
        });
      }
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
  <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a></div>
  <div class="page_title"><?php echo $string['loglatedetails'] ?></div>
</div>
  
<table class="header" id="maindata">
<thead>
<tr>
<th></th>
<th class="col"><?php echo $string['papertitle'] ?></th>
<th class="col" style="width:50%"><?php echo $string['studentslate'] ?></th>
</tr>
</thead>
<tbody>
<?php
  $icons = array('formative_16.gif', 'progress_16.gif', 'summative_16.gif');
  $data = array();

  $result = $mysqli->prepare("SELECT DISTINCT paper_type, paper_title, paperID, userID FROM log_metadata, log_late, properties, users WHERE log_late.metadataID = log_metadata.id AND log_metadata.paperID = properties.property_id AND log_metadata.userID = users.id AND (roles LIKE '%Student%' OR roles LIKE '%graduate%')");
  $result->execute();
  $result->bind_result($paper_type, $paper_title, $paperID, $uID);
  while ($result->fetch()) {
    $data[$paperID]['paper_title'] = $paper_title;
    $data[$paperID]['paper_type'] = $paper_type;
    $data[$paperID]['students'][] = $uID;
  }
  $result->close();
  
  foreach ($data as $paperID => $row) {
    echo "<tr><td class=\"icon\"><a href=\"../paper/details.php?paperID=$paperID\"><img src=\"../artwork/" . $icons[$row['paper_type']] . "\" width=\"16\" height=\"16\" alt=\"\" /></a></td><td><a href=\"../paper/details.php?paperID=$paperID\">" . $row['paper_title'] . "</a></td><td>" . count($row['students']) . "</td></tr>";
  }
?>
</tbody>
</table>

</div>
</body>
</html>
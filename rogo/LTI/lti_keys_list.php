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
 * Listing of IMS LTI Keys.
 *
 * @author Simon Atack
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
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>"/>
  
  <title>Rog&#333;: <?php echo $string['ltikeys'] . ' ' . $configObject->get('cfg_install_type') ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css"/>
  <link rel="stylesheet" type="text/css" href="../css/submenu.css"/>
  <link rel="stylesheet" type="text/css" href="../css/header.css"/>
  <link rel="stylesheet" type="text/css" href="../css/list.css"/>

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/list.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    function edit(lineID) {
      document.location.href = './edit_LTIkeys.php?LTIkeysid=' + lineID;
    }
    
    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({ 
          sortList: [[0,0]] 
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
	require '../include/lti_keys_options.inc';
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>
<div id="content">

<div class="head_title">
  <div><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-"/><a href="../admin/index.php"><?php echo $string['administrativetools']; ?></a></div>
  <div class="page_title"><?php echo $string['ltikeys'] ?></div>
</div>
  
<table id="maindata" class="header tablesorter" cellspacing="0" cellpadding="2" border="0" style="width:100%">
  <thead>
    <tr>
      <th class="col10" style="width:25%"><?php echo $string['name'] ?></th>
      <th class="col" style="width:25%"><?php echo $string['oauth_consume_key'] ?></th>
      <th class="col" style="width:25%"><?php echo $string['oauth_secret'] ?></th>
      <th class="col" style="width:25%"><?php echo $string['oauth_context_id'] ?></th>
    </tr>
  </thead>
  
  <tbody>
    <?php
    $id = 0;
    $result = $mysqli->prepare("SELECT id, oauth_consumer_key, secret, name, context_id FROM lti_keys WHERE deleted IS NULL");
    $result->execute();
    $result->bind_result($ltis['id'], $ltis['oauth_consumer_key'], $ltis['secret'], $ltis['name'], $ltis['context_id']);
    while ($result->fetch()) {
      $id = $ltis['id'];
      echo "<tr id=\"$id\" class=\"l\"><td class=\"col10\">" . $ltis['name'] . "</td><td>" . $ltis['oauth_consumer_key'] . "</td><td>" . $ltis['secret'] . "</td><td>" . $ltis['context_id'] . "</div></td></tr>\n";
    }
    $result->close();
    $mysqli->close();

    ?>
  </tbody>
</table>
</div>

</body>
</html>
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
require_once '../classes/question_status.class.php';

// Check if we have any faculties
$statuses = QuestionStatus::get_all_statuses($mysqli, $string);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

  <title>Rog&#333;: <?php echo $string['questionstatuses'] . ' ' . $configObject->get('cfg_install_type'); ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu_qstatus.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />

  <?php echo $configObject->get('cfg_js_root') ?>
  <script src="../js/jquery-1.11.1.min.js" type="text/javascript"></script>
  <script src="../js/jquery-ui-1.10.4.min.js" type="text/javascript"></script>
  <script src="../js/jquery.q_status.js" type="text/javascript"></script>
  <script src="../js/staff_help.js" type="text/javascript"></script>
  <script src="../js/list_ul.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
</head>

<body>
<?php
  require '../include/status_options.inc.php';
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>
  <div id="content">
    
  <div class="head_title">
    <img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" />
    <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a></div>
    <div class="page_title"><?php echo $string['questionstatuses'] ?></div>
  </div>

      <ul id="statuses" class="selectlist">
<?php
foreach ($statuses as $status) {
  $def_mod = ($status->get_is_default()) ? ' default' : '';
?>
        <li id="status_<?php echo $status->id ?>" class="selectable<?php echo $def_mod ?>" data-id="<?php echo $status->id ?>"><?php echo $status->get_name(); ?></li>
<?php
}
?>
      </ul>
  </div>
</body>
</html>
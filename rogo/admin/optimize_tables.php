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
  set_time_limit (0);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>Rog&#333;: <?php echo $string['optimizetables'] ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
	
  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
</head>

<body>
<?php
  require '../include/toprightmenu.inc';
	
	echo draw_toprightmenu(235);
?>
<div id="content">
  
<div class="head_title">
  <img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" />
  <div class="breadcrumb"><a href="../index.php"><?php echo $string['home'] ?></a><img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="./index.php"><?php echo $string['administrativetools'] ?></a></div>
  <div class="page_title"><?php echo $string['optimizetables'] ?></div>
</div>
  
<br />

<?php
  if (isset($_POST['submit'])) {
    ob_start();

    echo "<blockquote>\n";
    $getTables = $mysqli->query("SHOW TABLES");
    while ($table = $getTables->fetch_array(MYSQLI_NUM)) {
      if (isset($_POST[$table[0]]) and $_POST[$table[0]] == 1) {
        if (!$mysqli->query("OPTIMIZE TABLE " . $table[0])) {
          echo "<div>" . $mysqli->errno . ": " . $mysqli->error . "</div>\n";
        } else {
          echo "<div>" . $table[0] . " " . $string['optimized'] . "</div>\n";
        }
        flush();
        ob_flush();
      }
    }
    echo "<br /><br />" . $string['finished'] . "</blockquote>\n";
    
    ob_end_flush();
  } else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<blockquote>
<div><strong><?php echo $string['tables']; ?></strong></div>
<?php
  $getTables = $mysqli->query("SHOW TABLES");
  while ($table = $getTables->fetch_array(MYSQLI_NUM)) {
    echo "<input type=\"checkbox\" name=\"" . $table[0] . "\" value=\"1\" checked />" . $table[0] . "<br />\n";
  }
?>
<br />
<input class="ok" type="submit" name="submit" value="<?php echo $string['optimize'] ?>" />
</blockquote>
</form>
</div>
<?php
  }
  $mysqli->close();
?>
</body>
</html>
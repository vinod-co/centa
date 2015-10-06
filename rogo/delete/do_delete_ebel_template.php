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
* Delete an Ebel template - Admin only.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/admin_auth.inc';
require '../include/errors.inc';
  
$gridID = check_var('gridID', 'POST', true, false, true);

$row_no = 0;

$result = $mysqli->prepare("SELECT name FROM ebel_grid_templates WHERE id = ?");
$result->bind_param('i', $gridID);
$result->execute();
$result->store_result();
$result->bind_result($grid_name);
$result->fetch();
$row_no = $result->num_rows;
$result->close();

if ($row_no == 0) {
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
}

$result = $mysqli->prepare("DELETE FROM ebel_grid_templates WHERE id = ?");
$result->bind_param('i', $gridID);
$result->execute();  
$result->close();

$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title>Grid Deleted</title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/check_delete.css" />

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script>
    $(function () {
      window.opener.location.href = '../admin/list_ebel_grids.php';
      self.close();
    });
  </script>
</head>

<body>

<p>Grid template successfully deleted.<p>

<div class="button_bar">
<form action="" method="get">
<input type="button" name="cancel" value="OK" class="ok" onclick="self.opener.location.href='/admin/list_ebel_grids.php'; window.close();" />
</form>
</div>

</body>
</html>
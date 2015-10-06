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
* Confirm that it is OK to proceed deleting a user.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/sysadmin_auth.inc';
require_once '../include/errors.inc';
require_once '../classes/userutils.class.php';

$userID = check_var('id', 'GET', true, false, true);

// Check that all the past user IDs actually exist.
$id_list = explode(',', $userID);
foreach ($id_list as $id) {
  if ($id != '') {
    if (!UserUtils::userid_exists($id, $mysqli)) {
      $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
      $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
    }
  }
}

$user_no = count($id_list) - 1;

$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title><?php echo $string['confirmdelete']; ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/check_delete.css" />
</head>

<body>

<p>
<?php
  if ($user_no == 1) {
    echo $string['msg1'];
  } else {
    echo sprintf($string['msg2'], $user_no);
  }
?>
<p>
<div class="button_bar">
<form action="do_delete_user.php" method="post">
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
<input class="delete" type="submit" name="submit" value="<?php echo $string['delete']; ?>" /><input class="cancel" type="button" name="cancel" value="<?php echo $string['cancel']; ?>" onclick="javascript:window.close();" />
</form>
</div>

</body>
</html>
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
* Clear LTI links for a user - SysAdmin only.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/sysadmin_auth.inc';
require_once '../include/errors.inc';
require_once '../classes/userutils.class.php';

$userID = check_var('userID', 'POST', true, false, true);

$user_list = explode(',', $userID);

// We could be passed multiple user IDs.
foreach ($user_list as $individual_userID) {
  if ($individual_userID != '') {
    $user_details = UserUtils::get_user_details($individual_userID, $mysqli);
    if ($user_details === false) {
      $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
      $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['pagenotfound'], '../artwork/page_not_found.png', '#C00000', true, true);
    }
  }
}

foreach ($user_list as $individual_userID) {
  UserUtils::clear_lti_user($individual_userID, $mysqli);
}

$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title><?php echo $string['title'] ?></title>
  
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/check_delete.css" />

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script>
    $(function () {
      $('.ok').click(function() {
        window.close();
      });
    });
  </script>
</head>

<body>

<p><?php echo $string['msg'] ?><p>

<div class="button_bar">
<form action="" method="get">
<input type="button" name="cancel" value="<?php echo $string['ok'] ?>" class="ok" />
</form>
</div>

</body>
</html>

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
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require_once '../include/load_config.php';
require_once '../classes/formutils.class.php';
require_once '../include/auth.inc';
require_once '../classes/lang.class.php';
require_once '../classes/dbutils.class.php';
require_once '../classes/usernotices.class.php';
require_once '../classes/userutils.class.php';

$notice = UserNotices::get_instance();

$mysqli = DBUtils::get_mysqli_link($configObject->get('cfg_db_host'), $configObject->get('cfg_db_username'), $configObject->get('cfg_db_passwd'), $configObject->get('cfg_db_database'), $configObject->get('cfg_db_charset'), $notice, $configObject->get('dbclass'));

$password = $password_confirm = $email = '';
$message = '';
$critical_errors = array();
$errors = array();
$token = '';
$form_util = new FormUtils();

// Check if we've been passed a token
$token = (isset($_GET['token']) and $_GET['token'] != '') ? $_GET['token'] : ((!empty($_POST['token'])) ? $_POST['token'] : '');
if ($token == '') {
  $critical_errors[] = $string['notokensupplied'];
} else {
  // Check if the token exists and has not expired
  $stmt = $mysqli->prepare("SELECT id, user_id FROM password_tokens WHERE token = ? AND time > DATE_ADD(NOW(), INTERVAL -1 DAY) ORDER BY id DESC LIMIT 1");
  $stmt->bind_param('s', $token);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($id, $user_id);
  $stmt->fetch();
  if ($stmt->num_rows == 0) {
    $critical_errors[] = 'Invalid token';
  }
  $stmt->close();
}

if (count($critical_errors) == 0 and isset($_POST['token']) and $_POST['token'] != '') {
  // Process form submission
  $errors = $form_util->check_required(array('email' => $string['emailaddress'], 'password' => $string['password'], 'password_confirm' => $string['passwordconfirm']));
  if (!$form_util->is_email($_POST['email'])) {
    $email = $_POST['email'];
    $errors[] = $string['emailaddressinvalid'];
  }
  if($_POST['password'] != $_POST['password_confirm']) $errors[] = $string['passwordsnotmatch'];

  if (count($errors) == 0) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email address matches that of the user in the token record
    $stmt = $mysqli->prepare("SELECT username, email, roles FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($username, $existing_email, $userroles);
    $stmt->fetch();
    if ($stmt->num_rows == 0) {
      $critical_errors[] = $string['usernotfound'];
    } else {
      if ($email != $existing_email) {
        $errors[] = $string['incorrectemail'];
      } else {
        // Update user's password
        $success = UserUtils::update_password($username, $password, $user_id, $mysqli);

        if (!$success) {
          $errors[] = $string['databaseupdateerror'];
        } else {
          // Delete password token entry for this user
          $delete = $mysqli->prepare("DELETE FROM password_tokens WHERE user_id = ?");
          $delete->bind_param('i', $user_id);
          $delete->execute();
          $delete->close();

          $redirect_url = $configObject->get('cfg_root_path') . "/";
          if (strpos($userroles, 'External Examiner') !== false) {
            $redirect_url .= "reviews/";
          } elseif (strpos($userroles, 'Invigilator') !== false) {
            $redirect_url .= "invigilator/";
          } elseif (strpos($userroles, 'Student') !== false) {
            $redirect_url .= "students/";
          } elseif (strpos($userroles, 'Staff') !== false) {
            $redirect_url .= "staff/";
          }

          $message = $string['passwordupdated'] . ' <a href="' . $redirect_url . '">' . $string['login'] . '</a>.';
        }
      }
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

  <title><?php echo $string['resetpassword'] . ' ' . $configObject->get('cfg_install_type') ?></title>

  <link rel="stylesheet" href="../css/body.css" type="text/css" />
  <link rel="stylesheet" href="../css/screen.css" type="text/css" />
  <style type="text/css">
    body {font-size:90%}
    .field {padding-top:4px; padding-left:6px; font-weight:bold}
  </style>

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
  <script>
  $(function() {
    $('#forgotten_pw').validate({
      rules: {
        password_confirm: {
          required: true,
          equalTo: "#password"
        }
      },
      messages: {
        email: '<?php echo $string['emailaddressinvalid'] ?>',
        password: '<?php echo $string['pleaseenterpassword'] ?>',
        password_confirm: {
          required: '<?php echo $string['pleaseconfirmpassword'] ?>',
          equalTo: '<?php echo $string['passwordsnotmatch'] ?>'
        }
      }
    });
  });
  </script>
</head>

<body>
<form id="forgotten_pw" name="forgotten_pw" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<br />
	<div align="center">
  	<table cellpadding="0" cellspacing="0" style="width:500px; border:1px #C8C8C8 solid">
    	<tr style="height:70px; width:100%; background-color:#EAEAEA; font-size:150%; font-weight:bold; padding-left:6px"><td style="text-align:right; width:135px"><img src="../artwork/fingerprint_48.png" width="48" height="48" alt="fingerprint" /></td><td style="text-align:left">&nbsp;&nbsp;<?php echo $string['resetpassword'] ?></td></tr>
<?php
if($message == '') {
?>
    	<tr><td colspan="2" style="padding-top:4px; padding-left:6px;"><?php echo $string['enternewpassword'] ?></td></tr>
<?php
  if(count($critical_errors) > 0) {
?>
    	<tr>
    		<td colspan="2" style="padding-top:4px; padding-left:6px;">
    			<ul>
<?php
    foreach($critical_errors as $error) {
?>
						<li class="error"><?php echo $error ?></li>
<?php
    }
?>
    			</ul>
				</td>
			</tr>
<?php
  } else {
    if(count($errors) > 0) {
?>
    	<tr>
    		<td colspan="2" style="padding-top:4px; padding-left:6px;">
    			<ul>
<?php
      foreach($errors as $error) {
?>
						<li class="error"><?php echo $error ?></li>
<?php
      }
?>
    			</ul>
				</td>
			</tr>
<?php
  }
?>
    	<tr>
    		<td colspan="2">
    			<table border="0" style="width:100%; text-align:left">
    				<tr>
    					<td class="field" style="width: 180px"><label for="email"><?php echo $string['emailaddress'] ?></label></td>
    					<td>
    						<input type="text" id="email" name="email" value="<?php echo $email; ?>" style="width: 280px" class="required email" />
    					</td>
    				</tr>
    				<tr>
    					<td class="field"><label for="email"><?php echo $string['password'] ?></label></td>
    					<td>
    						<input type="password" id="password" name="password" value="<?php echo $password; ?>" style="width: 280px" class="required" />
    					</td>
    				</tr>
    				<tr>
    					<td class="field"><label for="email"><?php echo $string['confirmpassword'] ?></label></td>
    					<td>
    						<input type="password" id="password_confirm" name="password_confirm" value="<?php echo $password_confirm; ?>" style="width: 280px" class="required" />
    						<input type="hidden" name="token" value="<?php echo $token ?>" />
    					</td>
    				</tr>
    				<tr><td colspan="2" style="text-align:center"><input type="submit" name="submit" value="<?php echo $string['reset'] ?>"  class="ok" /></td></tr>
    				<tr><td colspan="2">&nbsp;</td></tr>
    			</table>
    		</td>
    	</tr>
<?php
  }
} else {
?>
    	<tr><td colspan="2" style="padding-top:4px; padding-left:6px;"><?php echo $message ?></td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
<?php
}
?>
    </table>
  </div>
<?php
//  $mysqli->close();
?>
</form>

</body>
</html>
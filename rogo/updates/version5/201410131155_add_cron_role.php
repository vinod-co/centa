<?php

$username = 'cron';
$password = gen_password(16);
$role = 'Staff,SysCron';

// Add cron user to config file.
$new_lines = array("// cron user login credentials\n","\$cfg_cron_user = '$username';\n", "\$cfg_cron_passwd = '$password';\n");
$target_line = '$percent_decimals';
$updater_utils->add_line($string, '$cfg_cron_user', $new_lines, 28, $cfg_web_root, $target_line, -2);

// Add cron user to database.
$usercheck = $updater_utils->count_rows("SELECT id FROM users WHERE username = '$username'");
if (!$usercheck) {
  $salt = UserUtils::get_salt();
  $encrypt_password = encpw($salt, $username, $password);
  $updater_utils->execute_query("INSERT INTO users (username, password, surname, roles) VALUES ('$username', '$encrypt_password', '$username', '$role')", true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
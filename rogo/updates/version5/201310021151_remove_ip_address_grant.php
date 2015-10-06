<?php

// Your code here

if ($updater_utils->has_grant($cfg_db_username, 'SELECT', 'ip_addresses', $cfg_web_host)) {
	$sql = "REVOKE SELECT ON " . $cfg_db_database . ".ip_addresses FROM '" . $cfg_db_username . "'@'" . $cfg_web_host . "'";
	$updater_utils->execute_query($sql, true);
}

if ($updater_utils->has_grant($cfg_db_staff_user, 'SELECT', 'ip_addresses', $cfg_web_host)) {
	$sql = "REVOKE SELECT ON " . $cfg_db_database . ".ip_addresses FROM '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
	$updater_utils->execute_query($sql, true);
}

if ($updater_utils->has_grant($cfg_db_student_user, 'SELECT', 'ip_addresses', $cfg_web_host)) {
	$sql = "REVOKE SELECT ON " . $cfg_db_database . ".ip_addresses FROM '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
	$updater_utils->execute_query($sql, true);
}

if ($updater_utils->has_grant($cfg_db_inv_username, 'SELECT', 'ip_addresses', $cfg_web_host)) {
	$sql = "REVOKE SELECT ON " . $cfg_db_database . ".ip_addresses FROM '" . $cfg_db_inv_username . "'@'" . $cfg_web_host . "'";
	$updater_utils->execute_query($sql, true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
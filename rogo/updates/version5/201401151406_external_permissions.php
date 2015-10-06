<?php

// Your code here

//brzsw 15/01/2014 - Add new grants for so external examiners can read computer lab information.
if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'client_identifiers', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".client_identifiers TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}
if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'labs', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".labs TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'properties_modules', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".properties_modules TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'log_extra_time', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".log_extra_time TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'log_lab_end_time', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".log_lab_end_time TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'schools', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".schools TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'paper_metadata_security', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".paper_metadata_security TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'modules_student', $cfg_web_host)) {
	$sql = "GRANT SELECT ON " . $cfg_db_database. ".modules_student TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}
	
/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
<?php

//brzsw 09/09/2014 - Add new grants for invigilator users needing amend log_metadata
if (!$updater_utils->has_grant($cfg_db_inv_username, 'SELECT, UPDATE', 'log_metadata', $cfg_web_host)) {
  $sql = "GRANT SELECT, UPDATE ON " . $cfg_db_database . ".log_metadata TO '" . $cfg_db_inv_username . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_inv_username, 'SELECT', 'temp_users', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".temp_users TO '" . $cfg_db_inv_username . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

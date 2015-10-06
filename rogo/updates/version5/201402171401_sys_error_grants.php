<?php

if (!$updater_utils->has_grant($cfg_db_staff_user, 'INSERT', 'sys_errors', $cfg_web_host)) {
  $sql = "GRANT INSERT ON " . $cfg_db_database . ".sys_errors TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

<?php

if (!$updater_utils->has_grant($cfg_db_student_user, 'SELECT', 'marking_override', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".marking_override TO '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}
/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

<?php

//brzsw 15/12/2014 - Add new grants for external examiner users
if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'paper_feedback', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".paper_feedback TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}
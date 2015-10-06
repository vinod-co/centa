<?php

// Add in permissions for Staff users.
if (!$updater_utils->has_grant($cfg_db_staff_user, 'SELECT, UPDATE', 'toilet_breaks', $cfg_web_host)) {
    $sql = "GRANT SELECT, UPDATE ON " . $cfg_db_database . ".toilet_breaks TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
    $updater_utils->execute_query($sql, true);
}
?>

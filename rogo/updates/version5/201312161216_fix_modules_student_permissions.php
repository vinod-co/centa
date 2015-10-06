<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

//cczsa1 16/12/2013 - Add new grants for student users needing select from properties_modules
if (!$updater_utils->has_grant($cfg_db_student_user, 'SELECT, INSERT', 'modules_student', $cfg_web_host)) {
  $sql = "GRANT SELECT, INSERT ON " . $cfg_db_database . ".modules_student TO '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

<?php

// 23/07/2013 (brzsw) - Add 'exam_announcements' table
if (!$updater_utils->does_table_exist('exam_announcements')) {
  $sql = <<< QUERY
CREATE TABLE `exam_announcements` (
  `paperID` mediumint(8) unsigned NOT NULL,
  `q_id` int(4) unsigned NOT NULL DEFAULT '0',
  `q_number` smallint(5) unsigned NOT NULL DEFAULT '0',
  `screen` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `msg` text,
  `created` datetime,
  UNIQUE INDEX `idx_paperID_q_id` (`paperID`,`q_id`));
QUERY;
  $updater_utils->execute_query($sql, true);
  
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".exam_announcements TO '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
  $sql = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $cfg_db_database . ".exam_announcements TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".exam_announcements TO '" . $cfg_db_inv_username . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

// 23/07/2013 (brzsw) - Add clarification setting config file.
$new_lines = array("  \$midexam_clarification = array('invigilators', 'students');\n");
$target_line = '$emergency_support_numbers';
$updater_utils->add_line($string, '$midexam_clarification', $new_lines, 60, $cfg_web_root, $target_line, 1);


/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

<?php

if (!$updater_utils->does_table_exist('save_fail_log')) {
  $sql = <<< QUERY
CREATE TABLE `save_fail_log` (
  `id` int(4) unsigned NOT NULL primary key auto_increment,
  `userID` int(10) unsigned NOT NULL,
  `paperID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `screen` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ipaddress` varchar(100) DEFAULT NULL,
  `failed` int(4) unsigned NOT NULL DEFAULT '0',
  INDEX `idx_paperID` (`paperID`));
QUERY;
  $updater_utils->execute_query($sql, true);
  
	// Add in permissions for staff users.
  $sql = "GRANT SELECT, INSERT ON " . $cfg_db_database . ".save_fail_log TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);

	// Add in permissions for student users.
  $sql = "GRANT INSERT ON " . $cfg_db_database . ".save_fail_log TO '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
	
}
?>
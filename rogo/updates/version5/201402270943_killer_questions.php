<?php

if (!$updater_utils->does_table_exist('killer_questions')) {
  $sql = <<< QUERY
CREATE TABLE `killer_questions` (
  `id` int(4) unsigned NOT NULL primary key auto_increment,
  `paperID` mediumint(8) unsigned NOT NULL,
  `q_id` int(4) unsigned NOT NULL DEFAULT '0',
  INDEX `idx_paperID` (`paperID`));
QUERY;
  $updater_utils->execute_query($sql, true);
  
	// Add in permissions for staff users.
  $sql = "GRANT SELECT, INSERT, UPDATE, DELETE ON " . $cfg_db_database . ".killer_questions TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);

	// Add in permissions for student users.
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".killer_questions TO '" . $cfg_db_student_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
	
}
?>
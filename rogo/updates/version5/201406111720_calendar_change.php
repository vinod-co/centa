<?php

if (!$updater_utils->does_table_exist('extra_cal_dates')) {
  $sql = <<< QUERY
CREATE TABLE `extra_cal_dates` (
  `id` int(11) unsigned NOT NULL primary key auto_increment,
  `title` varchar(255) NOT NULL,
  `message` text,
  `thedate` datetime NOT NULL,
  `duration` int(11) NOT NULL,          
  `bgcolor` varchar(16) NOT NULL,
  `deleted` datetime DEFAULT NULL);
QUERY;
  $updater_utils->execute_query($sql, true);

	// Add in permissions for staff users.
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".extra_cal_dates TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}
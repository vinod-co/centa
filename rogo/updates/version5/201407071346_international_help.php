<?php
// Add new columns to Staff Help.
if (!$updater_utils->does_column_exist('staff_help', 'language')) {
  $sql = "ALTER TABLE staff_help ADD COLUMN `language` char(5) NOT NULL DEFAULT 'en'";
  $updater_utils->execute_query($sql, true);

  $sql = "ALTER TABLE staff_help ADD COLUMN `articleid` smallint(6) unsigned NOT NULL";
  $updater_utils->execute_query($sql, true);

  $sql = "ALTER TABLE staff_help ADD COLUMN `lastupdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP";
  $updater_utils->execute_query($sql, true);
  
  $sql = "ALTER TABLE staff_help convert to character set utf8 collate utf8_unicode_ci";
  $updater_utils->execute_query($sql, true);
  
  $sql = "ALTER TABLE staff_help ADD INDEX (`language`)";
  $updater_utils->execute_query($sql, true);
    
  $sql = "ALTER TABLE staff_help ADD INDEX (`articleid`)";
  $updater_utils->execute_query($sql, true);
}

// Add new columns to Student Help.
if (!$updater_utils->does_column_exist('student_help', 'language')) {
  $sql = "ALTER TABLE student_help ADD COLUMN `language` char(5) NOT NULL DEFAULT 'en'";
  $updater_utils->execute_query($sql, true);

  $sql = "ALTER TABLE student_help ADD COLUMN `articleid` smallint(6) unsigned NOT NULL";
  $updater_utils->execute_query($sql, true);

  $sql = "ALTER TABLE student_help ADD COLUMN `lastupdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP";
  $updater_utils->execute_query($sql, true);
  
  $sql = "ALTER TABLE student_help convert to character set utf8 collate utf8_unicode_ci";
  $updater_utils->execute_query($sql, true);
  
  $sql = "ALTER TABLE student_help ADD INDEX (`language`)";
  $updater_utils->execute_query($sql, true);
    
  $sql = "ALTER TABLE student_help ADD INDEX (`articleid`)";
  $updater_utils->execute_query($sql, true);
}
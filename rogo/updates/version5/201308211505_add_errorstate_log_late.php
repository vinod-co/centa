<?php

if (!$updater_utils->does_column_exist('log_late', 'errorstate')) {
  $updater_utils->execute_query("ALTER TABLE log_late ADD COLUMN errorstate tinyint unsigned NOT NULL DEFAULT '0' AFTER user_answer", true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
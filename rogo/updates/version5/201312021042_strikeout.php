<?php

// Your code here
if (!$updater_utils->does_column_exist('special_needs', 'dismiss')) {
  $updater_utils->execute_query("ALTER TABLE special_needs ADD COLUMN dismiss varchar(20)", true);
}
/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

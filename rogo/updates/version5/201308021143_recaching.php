<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

if (!$updater_utils->does_column_exist('properties', 'recache_marks')) {
  $sql = 'ALTER TABLE properties ADD COLUMN recache_marks tinyint(3) unsigned DEFAULT 0';
  $updater_utils->execute_query($sql, true);
}
<?php

if (!$updater_utils->does_column_exist('log_metadata', 'highest_screen')) {
  $updater_utils->execute_query("ALTER TABLE log_metadata ADD COLUMN highest_screen tinyint unsigned", true);
}

if (!$updater_utils->does_column_exist('log_metadata_deleted', 'highest_screen')) {
  $updater_utils->execute_query("ALTER TABLE log_metadata_deleted ADD COLUMN highest_screen tinyint unsigned", true);
}
<?php

// Remove unused 'last_login' column.
if ($updater_utils->does_column_exist('users', 'last_login')) {
  $sql = "ALTER TABLE users DROP COLUMN last_login";
  $updater_utils->execute_query($sql, true);
}

// Make username field not null
if (!$updater_utils->is_column_nullable('users', 'username')) {
  $sql = "ALTER TABLE users CHANGE COLUMN username username char(60) NOT NULL";
  $updater_utils->execute_query($sql, true);
}

// Make password field not null
if (!$updater_utils->is_column_nullable('users', 'password')) {
  $sql = "ALTER TABLE users CHANGE COLUMN password password char(90) NOT NULL";
  $updater_utils->execute_query($sql, true);
}

// Make surname field not null
if (!$updater_utils->is_column_nullable('users', 'surname')) {
  $sql = "ALTER TABLE users CHANGE COLUMN surname surname char(35) NOT NULL";
  $updater_utils->execute_query($sql, true);
}
?>
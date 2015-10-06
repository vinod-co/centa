<?php

// Remove unused 'last_login' column.
if ($updater_utils->does_column_exist('modules_staff', 'type')) {
  $sql = "ALTER TABLE modules_staff DROP COLUMN type";
  $updater_utils->execute_query($sql, true);
}
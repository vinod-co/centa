<?php

// Add 'warn' column to status
if (!$updater_utils->does_column_exist('question_statuses', 'display_warning')) {
  $updater_utils->execute_query("ALTER TABLE question_statuses ADD COLUMN display_warning tinyint(3) DEFAULT 0 AFTER validate", true);

  $sql = ("UPDATE question_statuses SET display_warning = 1 WHERE id IN (2, 3, 5)");
  $updater_utils->execute_query($sql, true);
}


/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
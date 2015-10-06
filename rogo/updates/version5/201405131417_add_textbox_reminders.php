<?php

if (!$updater_utils->does_column_exist('textbox_marking', 'reminders')) {
  $sql = "ALTER TABLE `textbox_marking` ADD COLUMN `reminders` VARCHAR(255) NULL AFTER `student_userID`;";
  $updater_utils->execute_query($sql, true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
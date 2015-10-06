<?php

if ($updater_utils->does_column_exist('question_statuses', 'exclude_search')) {
  $sql = 'ALTER TABLE question_statuses CHANGE exclude_search retired tinyint(3) NOT NULL';
  $updater_utils->execute_query($sql, true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
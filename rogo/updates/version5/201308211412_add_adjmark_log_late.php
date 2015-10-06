<?php

if (!$updater_utils->does_column_exist('log_late', 'adjmark')) {
  $updater_utils->execute_query("ALTER TABLE log_late ADD COLUMN adjmark float AFTER mark", true);
  $updater_utils->execute_query("UPDATE log_late SET adjmark = mark", false);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
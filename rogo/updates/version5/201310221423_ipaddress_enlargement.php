<?php

// Your code here

if ($updater_utils->does_column_type_value_exist('log_metadata', 'ipaddress', 'char(15)')) {
  $updater_utils->execute_query("ALTER TABLE log_metadata CHANGE COLUMN ipaddress ipaddress varchar(100)", true);
}

if ($updater_utils->does_column_type_value_exist('log_metadata_deleted', 'ipaddress', 'char(15)')) {
  $updater_utils->execute_query("ALTER TABLE log_metadata_deleted CHANGE COLUMN ipaddress ipaddress varchar(100)", true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
<?php

if (!$updater_utils->does_table_exist('client_identifiers')) {
  $sql = 'RENAME TABLE ip_addresses TO client_identifiers';

  $updater_utils->execute_query($sql, true);


  $priv_SQL = array();
  $priv_SQL[] = "GRANT SELECT ON " . $cfg_db_database . ".client_identifiers TO '". $cfg_db_username . "'@'". $cfg_web_host . "'";
  $priv_SQL[] = "GRANT SELECT ON " . $cfg_db_database . ".client_identifiers TO '". $cfg_db_student_user . "'@'". $cfg_web_host . "'";
  $priv_SQL[] = "GRANT SELECT ON " . $cfg_db_database . ".client_identifiers TO '". $cfg_db_inv_username . "'@'". $cfg_web_host . "'";

  foreach ($priv_SQL as $sql) {
    $updater_utils->execute_query($sql, true);
  }
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

$new_lines = array("  \$cfg_long_date = '%d/%m/%Y';\n");

$target_line = '$cfg_short_date = ';
$updater_utils->add_line($string, '$cfg_long_date = ', $new_lines, 62, $cfg_web_root, $target_line, 1);
if (!isset($cfg_web_host)) {
  $cfg_web_host = $cfg_db_host;
}


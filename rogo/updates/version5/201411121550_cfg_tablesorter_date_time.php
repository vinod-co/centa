<?php

$new_lines = array("\$cfg_tablesorter_date_time = 'uk';\r\n");
$target_line = '$cfg_long_date_time ';
$updater_utils->add_line($string, '$cfg_tablesorter_date_time', $new_lines, 69, $cfg_web_root, $target_line, 1);

<?php

$new_lines = array("\$cfg_short_date_time = '%d/%m/%y %H:%i';\r\n");
$target_line = '$cfg_long_date_time ';
$updater_utils->add_line($string, '$cfg_short_date_time', $new_lines, 64, $cfg_web_root, $target_line, 1);

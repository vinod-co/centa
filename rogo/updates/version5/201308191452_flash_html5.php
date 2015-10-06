<?php

// Your code here

$new_lines = array("//Questions\n  \$cfg_interactive_qs = 'html5';\n");
$target_line = '$vle_apis';
$updater_utils->add_line($string, '$cfg_interactive_qs', $new_lines, 99, $cfg_web_root, $target_line, 1);


/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

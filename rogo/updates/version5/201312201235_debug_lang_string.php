<?php

// Your code here

//$debug_lang_string

$new_lines = array("//used for debugging \n  \$debug_lang_string = false;\n");
$target_line = '$display_auth_debug';
$updater_utils->add_line($string, '$debug_lang_string', $new_lines, 99, $cfg_web_root, $target_line, 1);


/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

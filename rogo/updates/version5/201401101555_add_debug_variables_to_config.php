<?php

// Your code here
//  public function add_line($string, $search, $new_lines, $default_line, $cfg_web_root, $target_line = '', $offset = 1) {
$new_lines = array("\r\n  \$display_auth_debug = false; // set this to deisplay debug on failed authentication\r\n\r\n\$displayerrors = false;  // overrides settings in php for errors not to be shown to screen (true enables)\r\n\r\n  \$displayallerrors = false; // display/logs any error the system has including notices (true enables)\r\n\r\n  \$errorshutdownhandling=true; //enables log at shutdown (allows you to catch reasons behind fatal errors etc including mysqli errors (true enables)\r\n\r\n  \$errorcontexthandling = 'improved'; //improved gives a good capture of context variables while filtering for security of display/saved data, basic captures all but doesnt run and security routines, none doesnt capture any context variables\r\n");
$target_line = '$dbclass ';
$updater_utils->add_line($string, '$displayerrors', $new_lines, 99, $cfg_web_root, $target_line, 1);



/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

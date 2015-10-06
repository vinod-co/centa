<?php

// Your code here


/**
 * Adds a new line to /config/config.inc.php if not already there.
 *
 * @param string $string 				- Language translations.
 * @param string $search  			- A string to look for to see if the new lines already exist
 * @param string $new_lines 		- An array of new lines to insert.
 * @param int $default_line 		- Default line number to add to if no $target_line is found
 * @param string $cfg_web_root 	- Path to the root of Rogo.
 * @param string $target_line 	- A string to find on a target line to act as a location for the new lines
 * @param int $offset 					- A plus or negative offset from $target_line to insert the new lines
 */

$new_line="\$lti_integration = 'UoN'; // UoN lti integration\r\n";
$updater_utils->replace_line($string, "\$lti_integration = 'config/integration-UoN/lti_integration.class.php';", $new_line, $cfg_web_root);


/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

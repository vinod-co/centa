<?php
	// Add three new security lines to help stop session hijacking.
  //$new_lines = array("\n// PHP session security settings\n", "ini_set('session.cookie_secure', 1);\n", "ini_set('session.cookie_httponly', 1);\n", "ini_set('session.use_only_cookies', 1);\n");
  //$target_line = '$rogo_version';
	//$search = 'session.cookie_secure';
  //$updater_utils->add_line($string, $search, $new_lines, 14, $cfg_web_root, $target_line, -1);
?>
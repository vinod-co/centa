<?php

// Your code here

if (!$updater_utils->does_column_exist('questions', 'guid')) {
  $updater_utils->execute_query("ALTER TABLE questions ADD COLUMN guid char(40)", true);

  // Populate with values.
	$stmt = $mysqli->prepare('SELECT q_id FROM questions');
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($q_id);
	while ($stmt->fetch()) {
	  $server_ipaddress = str_replace('.', '', NetworkUtils::get_server_address());
		$guid = $server_ipaddress . uniqid('', true);
		
		$update = $mysqli->prepare('UPDATE questions SET guid = ? WHERE q_id = ?');
		$update->bind_param('si', $guid, $q_id);
		$update->execute();
		$update->close();

	}
	$stmt->close();
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */
 
 
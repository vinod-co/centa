<?php

// Your code here

$index_name = 'username_index';
$index_column = 'username';

$result = $mysqli->prepare("SHOW INDEXES IN users WHERE Non_unique = 0 AND key_name = ? AND column_name = ?");
$result->bind_param('ss', $index_name, $index_column);
$result->execute();
$result->store_result();
$num_rows =  $result->num_rows;
$result->close();

if ($num_rows == 0) {
  $updater_utils->execute_query("ALTER TABLE users DROP INDEX username_index", true);
  $updater_utils->execute_query("ALTER TABLE users ADD UNIQUE username_index(username)", true);
}


if (!$updater_utils->does_column_type_value_exist('users', 'username', 'char(60)')) {
  $updater_utils->execute_query("ALTER TABLE users CHANGE COLUMN username username char(60)", true);
}

?>

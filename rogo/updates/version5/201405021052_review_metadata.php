<?php

if (!$updater_utils->does_table_exist('review_metadata')) {
  $sql = <<< QUERY
CREATE TABLE `review_metadata` (
  `id` int(11) unsigned NOT NULL primary key auto_increment,
  `reviewerID` int(10) unsigned NOT NULL,
  `paperID` mediumint(8) unsigned NOT NULL,
  `started` datetime DEFAULT NULL,
  `complete` datetime DEFAULT NULL,
  `review_type` enum('External','Internal') DEFAULT NULL,
  `ipaddress` varchar(100) DEFAULT NULL,
  `paper_comment` text NULL,          
  INDEX `idx_paperID` (`paperID`));
QUERY;
  $updater_utils->execute_query($sql, true);
  
  if (!$updater_utils->does_column_exist('review_comments', 'metadataID')) {
    $updater_utils->execute_query("ALTER TABLE review_comments ADD COLUMN metadataID int(11) unsigned NOT NULL DEFAULT 0", true);
  }
  
	// Add in permissions for staff users.
  $sql = "GRANT SELECT, INSERT, UPDATE ON " . $cfg_db_database . ".review_metadata TO '" . $cfg_db_staff_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);

	// Add in permissions for external examiner users.
  $sql = "GRANT SELECT, INSERT, UPDATE ON " . $cfg_db_database . ".review_metadata TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
  
  
  // Query the old 'review_comments' table and populate 'review_metadata'.
  $result = $mysqli->prepare("SELECT q_paper, reviewer, reviewed, review_type, ipaddress FROM review_comments GROUP BY reviewer, q_paper, reviewed ORDER BY reviewed");
  $result->execute();
  $result->store_result();
  $result->bind_result($q_paper, $reviewer, $reviewed, $review_type, $ipaddress);
  while ($result->fetch()) {
    $insertID = $updater_utils->execute_query("INSERT INTO review_metadata VALUES(NULL, $reviewer, $q_paper, '$reviewed', NULL, '$review_type', '$ipaddress', NULL)", false);

    $updater_utils->execute_query("UPDATE review_comments SET metadataID = $insertID WHERE q_paper = $q_paper AND reviewer = $reviewer AND reviewed = '$reviewed'", true);
  }
  $result->close();
}

if ($updater_utils->does_column_exist('review_comments', 'q_paper')) {
  // Remove old column from 'review_comments' table.
  $updater_utils->execute_query("ALTER TABLE review_comments DROP COLUMN q_paper", true);
  $updater_utils->execute_query("ALTER TABLE review_comments DROP COLUMN reviewer", true);
  $updater_utils->execute_query("ALTER TABLE review_comments DROP COLUMN reviewed", true);
  $updater_utils->execute_query("ALTER TABLE review_comments DROP COLUMN review_type", true);
  $updater_utils->execute_query("ALTER TABLE review_comments DROP COLUMN ipaddress", true);
}

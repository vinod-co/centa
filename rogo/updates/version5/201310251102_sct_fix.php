<?php
if (!$updater_utils->has_updated('sct_fix')) {
//if (!file_exists("./stopfile_sct_fix.txt")) {
  // Add some temporary indexes to speed up update.
  for ($i=0; $i<3; $i++) {
    $result = $mysqli->prepare("ALTER TABLE log$i ADD INDEX tmp_q_idx(q_id)");
    $result->execute();
    $result->close();
  }

  // Get all SCT questions
  $result = $mysqli->prepare("SELECT q_id FROM questions WHERE q_type='sct'");
  $result->execute();
  $result->store_result();
  $result->bind_result($q_id);
  while ($result->fetch()) {

    for ($i=0; $i<3; $i++) {
      $update = $mysqli->prepare("UPDATE log$i SET totalpos = 1 WHERE q_id = ?");
      $update->bind_param('i', $q_id);
      $update->execute();
      $update->close();
    }

    $update = $mysqli->prepare("UPDATE log_late SET totalpos = 1 WHERE q_id = ?");
    $update->bind_param('i', $q_id);
    $update->execute();
    $update->close();

  }
  $result->close();

  // Remove the temporary indexes.
  for ($i=0; $i<3; $i++) {
    $result = $mysqli->prepare("ALTER TABLE log$i DROP INDEX tmp_q_idx");
    $result->execute();
    $result->close();
  }

  echo "<li>Updated SCT totalpos values.</li>";
  //touch("./stopfile_sct_fix.txt");
  $updater_utils->record_update('sct_fix');
}

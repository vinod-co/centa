<?php

if (!$updater_utils->has_updated('textbox_fix')) {
  // Add some temporary indexes to speed up update.
  for ($i=0; $i<3; $i++) {
    $result = $mysqli->prepare("ALTER TABLE log$i ADD INDEX tmp_q_idx(q_id)");
    $result->execute();
    $result->close();
  }

  // Get all SCT questions
  $result = $mysqli->prepare("SELECT q_id, marks_correct FROM questions, options WHERE questions.q_id = options.o_id AND q_type='textbox'");
  $result->execute();
  $result->store_result();
  $result->bind_result($q_id, $marks_correct);
  while ($result->fetch()) {

    for ($i=0; $i<3; $i++) {
      $update = $mysqli->prepare("UPDATE log$i SET totalpos = ? WHERE q_id = ?");
      $update->bind_param('ii', $marks_correct, $q_id);
      $update->execute();
      $update->close();
    }

    $update = $mysqli->prepare("UPDATE log_late SET totalpos = ? WHERE q_id = ?");
    $update->bind_param('ii', $marks_correct, $q_id);
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

  echo "<li>Updated Textbox totalpos values.</li>";
  $updater_utils->record_update('textbox_fix');
}

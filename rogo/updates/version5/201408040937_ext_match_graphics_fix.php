<?php
if (!$updater_utils->has_updated('ext_match_graphics_fix')) {

  $select_sql = 'SELECT q_id FROM questions WHERE q_type = "extmatch"';
  $result = $mysqli->prepare($select_sql);
  $result->execute();
  $result->store_result();
  $result->bind_result($q_id);
  while ($result->fetch()) {
    update_record($q_id, $mysqli);
  }
  $result->close();
  
  $updater_utils->record_update('ext_match_graphics_fix');
}

function update_record($q_id, $db) {
  $select_sql = "UPDATE options SET o_media='' WHERE o_id = ?";
  $result = $db->prepare($select_sql);
  $result->bind_param('i', $q_id);
  $result->execute();
  $result->close();
}

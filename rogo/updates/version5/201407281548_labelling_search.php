<?php
if (!$updater_utils->has_updated('labelling_search')) {

  $select_sql = 'SELECT q_id, correct FROM questions, options WHERE questions.q_id = options.o_id AND q_type = "labelling"';
  $result = $mysqli->prepare($select_sql);
  $result->execute();
  $result->store_result();
  $result->bind_result($q_id, $correct);
  while ($result->fetch()) {
    $first_split = explode(';', $correct);

    $option_text = '';
    if (isset($first_split[11])) {
      $s_split = explode('|', $first_split[11]);
      foreach ($s_split as $ind_label) {
        $label_parts = explode('$', $ind_label);
        if (isset($label_parts[4]) and trim($label_parts[4]) != '') {
          $option_text .= ',' . $label_parts[4];
        }
      }

      update_question($q_id, $option_text, $mysqli);
    }

  }
  $result->close();

  $updater_utils->record_update('labelling_search');
}


function update_question($q_id, $option_text, $db) {
  $select_sql = 'UPDATE options SET option_text = ? WHERE o_id = ?';
  $result = $db->prepare($select_sql);
  $result->bind_param('si', $option_text, $q_id);
  $result->execute();
  $result->close();
}

  
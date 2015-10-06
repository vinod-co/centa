<?php
if (!$updater_utils->has_updated('textbox_update')) {
//if (!file_exists("./stopfile_textbox_update.txt")) {
  $select_sql = <<< SEL
  SELECT o.id_num, o.o_id, o.option_text, o.correct, q.settings
  FROM options o
  INNER JOIN questions q ON o.o_id = q.q_id
  WHERE q_type='textbox'
SEL;

  $result = $mysqli->prepare($select_sql);
  $result->execute();
  $result->store_result();
  $result->bind_result($opt_id, $q_id, $editor, $terms, $settings);
  $rows = $result->num_rows;
  if ($rows > 0) {
    $update_o_sql = "UPDATE options SET option_text='', correct='placeholder' WHERE id_num=?";
    $o_update = $mysqli->prepare($update_o_sql);
    
    $update_q_sql = "UPDATE questions SET settings=? WHERE q_id=?";
    $q_update = $mysqli->prepare($update_q_sql);
    
    while ($result->fetch()) {
      $o_update->bind_param('i', $opt_id);
      $o_update->execute();

      $settings_new = json_decode($settings, true);
      $settings_new['editor'] = $editor;
      $settings_new['terms'] = (isset($terms) and !is_null($terms)) ? $terms : '';
      $settings_new = json_encode($settings_new);

      $q_update->bind_param('si', $settings_new, $q_id);
      $q_update->execute();
    }
  }
  $result->free_result();
  $result->close();
  if ($rows > 0) {
    echo "<li>Converted textbox questions to new format</li>\n";
  }

  //touch("./stopfile_textbox_update.txt");
  $updater_utils->record_update('textbox_update');
}
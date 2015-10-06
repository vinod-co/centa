<?php

if (!$updater_utils->does_column_type_value_exist('paper_notes', 'note_workstation', 'char(100)')) {
  $updater_utils->execute_query("ALTER TABLE paper_notes CHANGE COLUMN note_workstation note_workstation char(100)", true);
}
?>
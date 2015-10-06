<?php
// 30/09/2013 (brzsw) - put unique index on the textbox_marking table so the update on duplicate works.
if (!$updater_utils->does_index_exist('textbox_marking', 'idx_unique')) {
  $updater_utils->execute_query("ALTER TABLE textbox_marking ADD UNIQUE idx_unique(phase, answer_id, logtype)", true);
}
?>
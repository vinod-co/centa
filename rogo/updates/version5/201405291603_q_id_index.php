<?php

for ($i=0; $i<=6; $i++) {
  if (!$updater_utils->does_index_exist("log$i", 'q_id')) {
    $updater_utils->execute_query("ALTER TABLE log$i ADD INDEX (q_id)", true);
  }
}

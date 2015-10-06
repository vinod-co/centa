<?php
// Some questions have -1 for a status as a result of  abug.
// Reset any -1 status to 1.
if (!$updater_utils->has_updated('status_fix')) {
  $update = $mysqli->prepare("UPDATE questions SET status = 1 WHERE status = -1");
  $update->execute();
  $update->close();
  
  echo "<li>Question status fix.</li>";
  $updater_utils->record_update('status_fix');
}
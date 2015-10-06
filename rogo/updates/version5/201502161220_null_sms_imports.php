<?php

// Set deletions count to zero where null.
if ($updater_utils->count_rows("SELECT * FROM sms_imports WHERE deletions IS NULL") > 0) {
    $sql = "UPDATE sms_imports SET deletions = 0 WHERE deletions IS NULL";
    $updater_utils->execute_query($sql, true);
}

// Set enrolements count to zero where null.
if ($updater_utils->count_rows("SELECT * FROM sms_imports WHERE enrolements IS NULL") > 0) {
    $sql = "UPDATE sms_imports SET enrolements = 0 WHERE enrolements IS NULL";
    $updater_utils->execute_query($sql, true);
}
?>

<?php

// Your code here

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'question_exclude', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".question_exclude TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'users_metadata', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".users_metadata TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'marking_override', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".marking_override TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'sid', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".sid TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'student_notes', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".student_notes TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'paper_notes', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".paper_notes TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'exam_announcements', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".exam_announcements TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'relationships', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".relationships TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->has_grant($cfg_db_external_user, 'SELECT', 'feedback_release', $cfg_web_host)) {
  $sql = "GRANT SELECT ON " . $cfg_db_database . ".feedback_release TO '" . $cfg_db_external_user . "'@'" . $cfg_web_host . "'";
  $updater_utils->execute_query($sql, true);
}

if (!$updater_utils->does_column_type_value_exist('feedback_release', 'type', "enum('objectives','questions','cohort_performance','external_examiner')")) {
  $updater_utils->execute_query("ALTER TABLE feedback_release CHANGE type type enum('objectives','questions','cohort_performance','external_examiner')", true);
}

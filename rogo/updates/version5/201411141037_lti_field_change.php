<?php

if (!$updater_utils->does_column_type_value_exist('lti_user', 'lti_user_equ', 'int(10) unsigned')) {
  $updater_utils->execute_query("ALTER TABLE lti_user CHANGE COLUMN lti_user_equ lti_user_equ int(10) unsigned", true);
}
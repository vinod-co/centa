<?php

if (!$updater_utils->does_column_exist('special_needs', 'medical')) {
  $updater_utils->execute_query("ALTER TABLE special_needs ADD COLUMN medical text", true);
}

if (!$updater_utils->does_column_exist('special_needs', 'breaks')) {
  $updater_utils->execute_query("ALTER TABLE special_needs ADD COLUMN breaks text", true);
}
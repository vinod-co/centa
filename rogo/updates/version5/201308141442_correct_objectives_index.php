<?php

if ($updater_utils->does_index_exist('objectives', 'idx_identifier_calendar_year_objective300_sequence')) {
    $sql="ALTER TABLE `objectives` DROP INDEX `idx_identifier_calendar_year_objective300_sequence`, ADD INDEX `idx_identifier_calendar_year_sequence` ( `identifier` , `calendar_year` , `sequence` ) ";
    $updater_utils->execute_query($sql, true);
}

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

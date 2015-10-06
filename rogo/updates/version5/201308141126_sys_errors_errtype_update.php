<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

if (!$updater_utils->does_column_type_value_exist('sys_errors', 'errtype',"enum('Notice','Warning','Fatal Error','Unknown','Application Warning','Application Error')")) {
    $sql = "ALTER TABLE `sys_errors` CHANGE COLUMN `errtype` `errtype` enum('Notice','Warning','Fatal Error','Unknown','Application Warning','Application Error') DEFAULT NULL";
    $updater_utils->execute_query($sql, true);
}


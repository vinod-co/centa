<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

if (!$updater_utils->does_index_exist('ebel','PRIMARY')) {
  $updater_utils->execute_query("ALTER TABLE ebel ADD PRIMARY KEY (std_setID,category)", true);
}

if (!$updater_utils->does_index_exist('hofstee','PRIMARY')) {
  $updater_utils->execute_query("ALTER TABLE hofstee ADD PRIMARY KEY (std_setID)", true);
}

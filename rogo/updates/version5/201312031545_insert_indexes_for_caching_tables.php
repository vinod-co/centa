<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */



if (!$updater_utils->does_index_exist('cache_paper_stats','PRIMARY')) {
  $updater_utils->execute_query("ALTER TABLE cache_paper_stats ADD PRIMARY KEY (`paperID`)", true);
}

if ($updater_utils->does_index_exist('cache_paper_stats','paperID')) {
  $updater_utils->execute_query("ALTER TABLE cache_paper_stats DROP INDEX paperID", true);
}

if (!$updater_utils->does_index_exist('cache_student_paper_marks','idx_userID')) {
  $updater_utils->execute_query("ALTER TABLE cache_student_paper_marks ADD KEY idx_userID (`userID`)", true);
}



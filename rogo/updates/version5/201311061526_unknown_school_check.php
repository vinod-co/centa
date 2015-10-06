<?php

// Your code here

/*
 *****   NOW UPDATE THE INSTALLER SCRIPT   *****
 */

$configObj = Config::get_instance();
$cfg_web_root = $configObj->get('cfg_web_root');

// 15/08/2012 - cczsa1 adding unknown school and faculty
require_once $cfg_web_root . 'classes/facultyutils.class.php';
require_once $cfg_web_root . 'classes/schoolutils.class.php';

$facultystatus = FacultyUtils::facultyname_exists('UNKNOWN Faculty', $mysqli);
if (!FacultyUtils::facultyname_exists('UNKNOWN Faculty', $mysqli)) {
  $facultyID = FacultyUtils::add_faculty('UNKNOWN Faculty', $mysqli);
  echo "<li>Adding Unknown Faculty</li>\n";
} else {
  $facultyID = FacultyUtils::facultyid_by_name('UNKNOWN Faculty', $mysqli);
}

if (!SchoolUtils::school_exists_in_faculty($facultyID, 'UNKNOWN School', $mysqli) and $facultyID !== false) {
  $schoolID = SchoolUtils::add_school($facultyID, 'UNKNOWN School', $mysqli);
  echo "<li>Adding Unknown School</li>\n";
}


$result = $mysqli->prepare("SELECT id FROM " . $cfg_db_database . ".`schools`  WHERE school='UNKNOWN School'");
$result->execute();
$result->store_result();
$result->bind_result($id1);
$result->fetch();
$rows = $result->num_rows();
$result->free_result();
$result->close();
if ($rows == 0) {
  $schoolID = SchoolUtils::add_school($facultyID, 'UNKNOWN School', $mysqli);
  echo "<li>Adding Unknown School</li>\n";
}

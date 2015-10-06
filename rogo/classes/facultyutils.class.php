<?php
// This file is part of Rogō
//
// Rogō is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogō is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogō.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * Utility class for Faculty related functionality
 *
 * @author Anthony Brown, Simon Wilkinson
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


Class FacultyUtils {

  /**
   * Returns a name for a given faculty ID.
   * @param string $facultyID - The ID of the faculty to be checked
   * @param object $db        - Link to mysqli
   * @return bool|string      - False if the faculty does not exist, otherwise returns the name.
   */
  static function faculty_name_by_id($facultyID, $db) {
    $faculty_name = false;
    
    $result = $db->prepare("SELECT name FROM faculty WHERE id = ? AND deleted IS NULL");
    $result->bind_param('i', $facultyID);
    $result->execute();
    $result->store_result();
    $result->bind_result($faculty_name);
    $result->fetch();
    $result->close();

    return $faculty_name;
  }
  
  /**
   * Checks if a faculty name already exists.
   * @param string $facultyname - The ID of the faculty to be checked
   * @param object $db        - Link to mysqli
   * @return bool             - True if the faculty ID already exists and is not deleted
   */
  static function facultyname_exists($facultyname, $db) {
    $result = $db->prepare("SELECT id FROM faculty WHERE name = ? AND deleted IS NULL");
    $result->bind_param('s', $facultyname);
    $result->execute();
    $result->store_result();
    $result->bind_result($tmp_paperid);
    $result->fetch();
    if ($result->num_rows == 0) {
      $exist = false;
    } else {
      $exist = true;
    }
    $result->free_result();
    $result->close();

    return $exist;
  }

  /**
   * gets faculty id by namename already exists.
   * @param string $facultyname - The ID of the faculty to be checked
   * @param object $db        - Link to mysqli
   * @return bool             - True if the faculty ID already exists and is not deleted
   */
  static function facultyid_by_name($facultyname, $db) {
    $result = $db->prepare("SELECT id FROM faculty WHERE name = ? AND deleted IS NULL");
    $result->bind_param('s', $facultyname);
    $result->execute();
    $result->store_result();
    $result->bind_result($tmp_facultyid);
    $result->fetch();
    if ($result->num_rows == 0) {
      $exist = false;
    } else {
      $exist = $tmp_facultyid;
    }
    $result->free_result();
    $result->close();

    return $exist;
  }

/**
 * Creates a new faculty.
 * @param string $faculty - The name of the faculty to be added
 * @param object $db      - Link to mysqli
 * @return int            - The last insert number from the database
 */
  static function add_faculty($faculty, $db) {
    if (trim($faculty) == '') {
      return false;
    }
  
    $result = $db->prepare("INSERT INTO faculty(name) VALUES(?)");
    $result->bind_param('s', $faculty);
    $result->execute();
    $result->close();
    if ($db->errno != 0) {
      return false;
    }

    return $db->insert_id;
  }
  
/**
 * Deletes a faculty by setting a flag.
 * @param string $facultyID - The ID of the faculty to be deleted
 * @param object $db        - Link to mysqli
 */
  static function delete_faculty($facultyID, $db) {
    if ($facultyID == '') {
      return false;
    }
  
    $result = $db->prepare("UPDATE faculty SET deleted = NOW() WHERE id = ?");
    $result->bind_param('i', $facultyID);
    $result->execute();  
    $result->close();
  }
  
}

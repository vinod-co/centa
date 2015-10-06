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
 * Utility class for course related functionality.
 *
 * @author Simon Wilkinson
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


Class CourseUtils {

  /**
   * Check for already existing and then add new course data into the database.
   *
   * @param integer $schoolid ID of the school the course belongs to
   * @param string $name code of the course e.g. B140
   * @param string $description a title for the course e.g. Neuroscience BSc
   * @param object $db database connection
   *
   * @return bool depending on insert success
   */
  static function add_course($schoolid, $name, $description, $db) {

    if ($name == '') {
      return false;
    }
    if (CourseUtils::course_exists($name, $db) === true) {
      return true;
    }

    if (!is_int($schoolid)) {
      //school name given not school id so convert
      $schoolid = SchoolUtils::get_school_id_by_name($schoolid, $db);
      if (!$schoolid) {
        return false;
      }
    }

    $result = $db->prepare("INSERT INTO courses VALUES (NULL, ?, ?, NULL, ?)");
    $result->bind_param('ssi', $name, $description, $schoolid);
    $result->execute();
    $result->close();

    if ($db->errno != 0) {
      return false;
    }

    return true;
  }

  /**
   * Deletes an existing course.
   *
   * @param string $name code of the course e.g. B140
   * @param object $db database connection
   *
   * @return bool depending on  success
   */
  static function delete_course($name, $db) {
    if (trim($name) == '') {
      return false;
    }
    
    $result = $db->prepare("DELETE FROM courses WHERE name = ? AND deleted IS NULL LIMIT 1");
    $result->bind_param('s', $name);
    $result->execute();
    $result->close();

    if ($db->errno != 0) {
      return false;
    }

    return true;
  }

  /**
   * Check to see if a course already exists.
   *
   * @param string $name name of the course to check
   * @param object $db database connection
   *
   * @return bool false=course does not exists, true=course exist
   */
  static function course_exists($name, $db) {
    // Check for unique course
    $exists = true;

    $result = $db->prepare("SELECT id FROM courses WHERE name = ? AND deleted IS NULL");
    $result->bind_param('s', $name);
    $result->execute();
    $result->store_result();
    if ($result->num_rows == 0) {
      $exists = false;
    }
    $result->free_result();
    $result->close();

    return $exists;
  }

  static function courseid_exists($courseID, $db) {
    $result = $db->prepare("SELECT id FROM courses WHERE id = ? AND deleted IS NULL");
    $result->bind_param('i', $courseID);
    $result->execute();
    $result->store_result();
    if ($result->num_rows == 0) {
      $exist = false;
    } else {
      $exist = true;
    }
    $result->free_result();
    $result->close();
    
    return $exist;
  }
  
  static function get_course_details_by_name($name, $db) {
    $result = $db->prepare("SELECT description, deleted, schoolid FROM courses WHERE name = ? LIMIT 1");
    $result->bind_param('s', $name);
    $result->execute();
    $result->store_result();
    $result->bind_result($description, $deleted, $schoolid);
    if ($result->num_rows == 0) {
      $details = false;
    } else {
      $result->fetch();
      $details = array('description'=>$description, 'deleted'=>$deleted, 'schoolid'=>$schoolid);
    }
    $result->close();
    
    return $details;
  }
  
}

?>
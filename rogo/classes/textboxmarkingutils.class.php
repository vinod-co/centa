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
 * Utility class for Textbox Marking related functionality
 *
 * @author Simon Wilkinson
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


Class textbox_marking_utils {

  /**
  * Returns an array of user IDs who are down for second marking.
  *
  * @param $paperID - ID of the paper to be used
  * @param $db      - Database connection
  * @return array   - List of users who are set for remarking.
  */
  static function get_remark_users($paperID, $db) {
    $remark_array = array();
    
    $result = $db->prepare("SELECT userID FROM textbox_remark WHERE paperID = ?");
    $result->bind_param('i', $paperID);
    $result->execute();
    $result->bind_result($userID);
    while ($result->fetch()) {
      $remark_array[$userID] = true;
    }
    $result->close();
    
    return $remark_array;
  }
    
	/**
	 * Converts a time/date from 20140301103059 into 01/03/2014 10:30.
	 * @param string $original - The date that needs to be convered.
	 */
  static function nicedate($original) {
    return substr($original, 6, 2) . '/' . substr($original, 4, 2) . '/' . substr($original, 0, 4) . ' ' . substr($original, 8, 2) . ':' . substr($original, 10, 2);
  }

}
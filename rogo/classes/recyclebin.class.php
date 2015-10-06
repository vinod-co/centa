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
* Utility class for date related functionality
* 
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

Class RecycleBin {  
	/**
	 * Get a list of recycle bin contents for the current user
   * @param object $userObj current user object
   * @param resource $db database connection
	 * @return array of recycle bin contents
	 */
  static function get_recyclebin_contents($userObj, $db) {
    $recycle_bin = array();
  
    // Query the Papers tables.
    $i = 0;
    $stmt = $db->prepare("SELECT property_id AS id, paper_type, paper_title, DATE_FORMAT(deleted,'%Y%m%d%H%i') AS deleted FROM properties WHERE paper_ownerID = ? AND deleted IS NOT NULL");
    $stmt->bind_param('i', $userObj->get_user_ID());
    $stmt->execute();
    $stmt->bind_result($id, $paper_type, $paper_title, $deleted);
    while ($stmt->fetch()) {
      $recycle_bin[$i]['id'] = $id;
      $recycle_bin[$i]['type'] = 'paper';
      $recycle_bin[$i]['name'] = $paper_title;
      $recycle_bin[$i]['deleted'] = $deleted;
      $recycle_bin[$i]['subtype'] = $paper_type;
      $i++;
    }
    $stmt->close();

    // Query the Questions tables.
    $stmt = $db->prepare("SELECT q_id AS id, q_type, leadin_plain, DATE_FORMAT(deleted,'%Y%m%d%H%i') AS deleted FROM questions WHERE ownerID = ? AND deleted IS NOT NULL");
    $stmt->bind_param('i', $userObj->get_user_ID());
    $stmt->execute();
    $stmt->bind_result($id, $q_type, $leadin_plain, $deleted);
    while ($stmt->fetch()) {
      $recycle_bin[$i]['id'] = $id;
      $recycle_bin[$i]['type'] = 'question';
      if ($q_type == 'sct') {
        $parts = explode('~', $leadin_plain);
        $recycle_bin[$i]['name'] = $parts[0];
      } else {
        $recycle_bin[$i]['name'] = $leadin_plain;
      }
      $recycle_bin[$i]['deleted'] = $deleted;
      $recycle_bin[$i]['subtype'] = $q_type;
      $i++;
    }
    $stmt->close();

    // Query the Folder tables.
    $stmt = $db->prepare("SELECT id, name, DATE_FORMAT(deleted,'%Y%m%d%H%i') AS deleted FROM folders WHERE ownerID = ? AND deleted IS NOT NULL");
    $stmt->bind_param('i', $userObj->get_user_ID());
    $stmt->execute();
    $stmt->bind_result($id, $name, $deleted);
    while ($stmt->fetch()) {
      $recycle_bin[$i]['id'] = $id;
      $recycle_bin[$i]['type'] = 'folder';
      $recycle_bin[$i]['name'] = str_replace(';','\\',$name);
      $recycle_bin[$i]['deleted'] = $deleted;
      $recycle_bin[$i]['subtype'] = '';
      $i++;
    }
    $stmt->close();
    
    return $recycle_bin;	
  }
  
}

?>
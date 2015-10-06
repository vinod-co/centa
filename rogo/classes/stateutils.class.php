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
* Class to load and save user interface state information from the database.
* This circumvents problems associated with using cookies (e.g. need visitor
* consent and are machine specific). 
* 
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

Class StateUtils {
  private $db;
  private $userID;
    
  public function __construct($userID, $db) {
    $this->db = $db;
    $this->userID = $userID;
  }
  
  /**
  * Obtains all state information for a given page.
  * @param string $page - The page to get the state for. If left blank the current page is used.
  * @return array       - Array of state content keyed by state name.
  */
  public function getState($page = '') {
    $state_array = array();
    if ($page == '') {
      $page = $_SERVER['PHP_SELF'];
    }
    
    $result = $this->db->prepare("SELECT state_name, content FROM state WHERE page = ? AND userID = ?");
    $result->bind_param('si', $page, $this->userID);
    $result->execute();
    $result->bind_result($state_name, $content);
    while ($result->fetch()) {
      $state_array[$state_name] = $content;
    }
    $result->close();
    
    return $state_array;
  }

  /**
  * Saves state information for a given page.
  * @param string $state_name - The name of the interface object we are saving state for.
  * @param string $content    - What the current state of the interface object is.
  * @param string $page       - The page in the system we are saving state for.
  */
  public function setState($state_name, $content, $page) {
    $result = $this->db->prepare("REPLACE INTO state (userID, state_name, content, page) VALUES (?, ?, ?, ?)");
    $result->bind_param('isss', $this->userID, $state_name, $content, $page);
    $result->execute();
  }

}

$stateutil = new StateUtils($userObject->get_user_ID(), $mysqli);
?>
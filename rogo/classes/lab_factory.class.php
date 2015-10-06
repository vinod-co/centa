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
 * Repository class for the labs table
 *
 * @author Ben Parish
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


class LabFactory {

  /**
   * @var mysqli $db
   */
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  /**
   * @param int $address - IP address of a machine in the required lab
   * @return Lab         - Lab object for specified IP address or false if not found
   */
  public function get_lab_based_on_client($address) {
    $sql = 'SELECT lab, name FROM client_identifiers, labs WHERE client_identifiers.lab = labs.id AND address = ?';

    $lab_results = $this->db->prepare($sql);
    $lab_results->bind_param('s', $address);
    $lab_results->execute();
    $lab_results->store_result();
    $lab_results->bind_result($lab_id, $room_name);
    if ($lab_results->num_rows < 1) {
      $lab_results->close();

      return false;
    }
    $lab_results->fetch();

    $lab_object = new Lab();

    $lab_object->set_id($lab_id);
    $lab_object->set_name($room_name);

    $lab_results->close();

    return $lab_object;
  }
}






























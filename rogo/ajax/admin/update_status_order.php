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
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../../include/sysadmin_auth.inc';

if (!isset($_POST['statuses'])) {
	$rval = 'ERROR';
} else {
  $rval = 'OK';
  $success = true;
  $mysqli->autocommit(false);

  parse_str($_POST['statuses'], $statuses);

  foreach ($statuses['status'] as $index => $id) {
    $sql = 'UPDATE question_statuses SET display_order = ? WHERE id = ?';
    $result = $mysqli->prepare($sql);
    $result->bind_param('ii', $index, $id);
    if (!$result->execute()) {
      $success = false;
    }
    $result->close();
  }

  if (!$success) {
    $mysqli->rollback();
    $rval = 'ERROR';
  } else {
    $mysqli->commit();
  }

  $mysqli->autocommit(true);
}

echo $rval;
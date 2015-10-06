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
* Check that a paper name has not already been used elsewhere.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../../include/staff_auth.inc';
require_once '../../include/errors.inc';
require_once '../../classes/paperutils.class.php';

$name = check_var('name', 'POST', true, false, true);

if (Paper_utils::is_paper_title_unique($name, $mysqli)) {
  echo 'unique';
} else {
  echo 'duplicate';
}

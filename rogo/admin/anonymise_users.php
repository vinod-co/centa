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
* This script will jumble up names in the user table. This is useful to anonymise
* live data if taking Rogo, for example, on a laptop to a conference. Under no
* circumstances run this on a live installation.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/sysadmin_auth.inc';
require '../classes/anonymise.class.php';

$anonymiseObj = new Anonymise($mysqli);

$anonymiseObj->check_security();

$anonymiseObj->load_names();

$anonymiseObj->process_names();

$anonymiseObj->process_sids();

echo 'Done';

?>
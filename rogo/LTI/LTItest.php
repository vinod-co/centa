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

require_once 'ims-lti\UoN_LTI.php';
$root = str_replace('/include', '/', str_replace('\\', '/', dirname(__FILE__)));
$root = $root . '/../';

require_once  $root . 'include/load_config.php';
require_once $cfg_web_root . 'classes/dbutils.class.php';

$mysqli = DBUtils::get_mysqli_link($cfg_db_host, $cfg_db_username, $cfg_db_passwd, $cfg_db_database, $cfg_db_charset, $notice, $dbclass);

$lti = new UoN_LTI($mysqli);
$lti->init_lti0($mysqli);
$lti->init_lti(true, false);

print "<pre>";
print_r($lti);


var_dump($lti);


echo $lti->dump();

print "</pre>";
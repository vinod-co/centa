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
 * checks lookup plugin functionality
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


if (isset($_REQUEST['var_display_max_depth'])) {
  $max_depth = $_REQUEST['var_display_max_depth'];
} else {
  $max_depth = 1024;
}
ini_set('xdebug.var_display_max_depth', $max_depth);
print 'xdebug.var_display_max_depth: ' . $max_depth . '<br />';

if (isset($_REQUEST['var_display_max_children'])) {
  $max_chldrn = $_REQUEST['var_display_max_children'];
} else {
  $max_chldrn = 10;
}
ini_set('xdebug.var_display_max_depth', $max_chldrn);
print 'xdebug.var_display_max_depth: ' . $max_chldrn . '<br />';

$root = str_replace('\testing', '/', str_replace('\\', '/', dirname(__FILE__)));
//print "$root ::: " . __FILE__ ;
require_once $root . '/../include/auth.inc';
include_once $root . '/../include/load_config.php';
$cfg_web_root = $configObject->get('cfg_web_root');
require_once $cfg_web_root . 'classes/configobject.class.php';
require_once $cfg_web_root . 'classes/lang.class.php';
require_once $cfg_web_root . 'lang/' . $language . '/include/common.inc'; // Include common language file that all scripts need
require_once $cfg_web_root . 'include/custom_error_handler.inc';
require_once $cfg_web_root . 'classes/dbutils.class.php';
require_once $cfg_web_root . 'classes/userobject.class.php';
require_once $cfg_web_root . 'classes/authentication.class.php';
require_once $cfg_web_root . 'classes/lang.class.php';

require_once $cfg_web_root . '/classes/moduleutils.class.php';
require_once $cfg_web_root . '/classes/schoolutils.class.php';
require_once $cfg_web_root . '/classes/usernotices.class.php';

require_once '../include/admin_auth.inc';

if (!$userObject->has_role('SysAdmin')) {
  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], $msg, $string['accessdenied'], $configObject->get('cfg_root_path') . '/artwork/page_not_found.png', '#C00000', true, true);
}

$configObj=Config::get_instance();

$lookup = Lookup::get_instance($configObj, $mysqli);


$newdata = new stdClass();


if(!isset($_REQUEST['lookuptesttype'])) {
  $_REQUEST['lookuptesttype']='user';
}
$list='';
foreach ($_REQUEST as $key => $value) {
  if ($key != 'lookuptesttype' and $key != 'ROGO_PW') {
    $newdata->$key = $value;
    $list = $list . " $key = $value";
  }
}

echo "use lookuptesttype to set type of lookup anything else taken as a key value pair<br>";
echo "<h1>looking up via $_REQUEST[lookuptesttype] for $list</h1>";

$data = new stdClass();
$data->lookupdata = $newdata;

$funcname=$_REQUEST['lookuptesttype'] . 'lookup';

echo "<h2>Info in</h2>";
print "<pre>";
var_dump($data->lookupdata);
print "</pre>";


$info = $lookup->$funcname($data);




echo "<h2>Info back</h2>";
print "<pre>";
var_dump($info->lookupdata);
print "</pre>";
echo "<br>\r\n";
echo "<h2>Lookup Debug</h2>";
$lookupdebug = $lookup->debug_as_array();

echo "<pre>";
foreach ($lookupdebug as $line) {
  print $line ."\r\n";
}
print "<br>\r\n<h2>dump of return</h2>";
var_dump($info);
echo "</pre>";

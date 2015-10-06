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
 * An authentication checking page.
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


$root = str_replace('/include', '/', str_replace('\\', '/', dirname(__FILE__)));
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



LangUtils::loadlangfile('admin/detailed_authentication_info.php');

if (is_null($configObject->get('cfg_db_port'))) {
  $configObject->set('cfg_db_port', 3306);
}
$mysqli = DBUtils::get_mysqli_link($configObject->get('cfg_db_host'), $configObject->get('cfg_db_username'), $configObject->get('cfg_db_passwd'), $configObject->get('cfg_db_database'), $configObject->get('cfg_db_charset'), $notice, $configObject->get('dbclass'), $configObject->get('cfg_db_port'));


$notice=UserNotices::get_instance();

if(is_null($configObject->get('display_auth_debug'))) {
  $notice->display_notice_and_exit($mysqli, $string['pagenotfound'], '"display_auth_debug" not correctly set in the config file', $string['accessdenied'], $configObject->get('cfg_root_path') . '/artwork/page_not_found.png', '#C00000', true, true);
}


if($configObject->get('cfg_session_name')!='') {
  session_name($configObject->get('cfg_session_name'));
} else {
  session_name('RogoAuthentication');
}
$return = session_start();
$authentication = new Authentication($configObject, $mysqli, $_REQUEST, $_SESSION);
$result=$authentication->do_authentication($string);

$authinfo = $authentication->version_info();

$plugin_no = count($authinfo->plugins);

echo "<h3>User Info:</h3>";
$getauthobj = new stdClass();
$authentication->get_auth_obj($getauthobj);
$userObject = UserObject::get_instance();
$userObject->debug();

echo "<h3>Authentication Info:</h3><table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" style=\"margin:10px\">\n";
echo "<tr><td class=\"sechead\">" . $string['No'] . "</td><td class=\"sechead\">" . $string['Name'] . "</td><td class=\"sechead\">" . $string['Class'] . "</td><td class=\"sechead\">" . $string['Version'] . "</td></tr>";

for ($i=1; $i<$plugin_no; $i++) {
  echo "<tr><td>" . $authinfo->plugins[$i]->number . ".</td><td><nobr>" . $authinfo->plugins[$i]->name . "</nobr></td><td>" . $authinfo->plugins[$i]->classname . "</td><td>" . $authinfo->plugins[$i]->version . "</td></tr>\n";
}
echo "</table>\n";
echo "<br />\n";

echo "<h3>Authentication Debug:</h3>";
$authentication->display_debug();
$included_files = get_included_files();

//set string encoding for the mbstring module for interfaces
mb_internal_encoding($configObject->get('cfg_page_charset'));
echo "<h3>Included Files:</h3>";
var_dump($included_files);

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

$configObject = Config::get_instance();
require_once $configObject->get('cfg_web_root') . 'classes/userutils.class.php';

class lti_integration {

  public $description = 'Default';
  static function load() {

    // Load the appropriate LTI integration class (if new one found load that else use this)

    $configObject = Config::get_instance();

    if (!is_null($configObject->get('lti_integration')) and $configObject->get('lti_integration') != '' and $configObject->get('lti_integration') != 'default') {
      $inc_file = $configObject->get('cfg_web_root') . 'plugins/LTI/' . $configObject->get('lti_integration') . '.class.php';
      if (file_exists($inc_file)) {
        require_once $inc_file;
      } else {
        echo "LTI Plugin not found: $inc_file";
        exit;
      }
      return new lti_integration_extended();
    } else {
      require_once $configObject->get('cfg_web_root') . '/plugins/LTI/' . 'default.class.php';

      return new lti_integration_extended();
    }
  }

}

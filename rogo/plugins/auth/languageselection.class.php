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
 * handles language selection at login
 * Adds a language selection box to the login form
 *
 * @author Anthony Brown, Josef Martinak
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 * 
 *  To enable add the following to the Authentication settings ($authentication) in config.inc.php
 * 
 *   array( 
 *          'languageselection', 
 *          array( 'available_languages'=>array('English'=>'en','Polski'=>'pl'),'cfg_web_root'=>$cfg_web_root), 
 *          'Language Selection'
 *         ),
 * 
 */

$cfg_web_root = $configObject->get('cfg_web_root');
require_once 'outline_authentication.class.php';
require_once $cfg_web_root . '/classes/lang.class.php';

class Languageselection_auth extends outline_authentication {

  public $impliments_api_auth_version = 1;
  public $version = 0.9;

  function register_callback_routines() {

    $callbackarray[] = array(array($this, 'add_language_selection'), 'displaystdform', $this->number, $this->name);
    $callbackarray[] = array(array($this, 'store_data'), 'postauth', $this->number, $this->name);

    return $callbackarray;
  }

  function store_data($sessionstoreobj) {
	  global $string;
		
		$configObj = Config::get_instance();
		$cfg_web_root = $configObj->get('cfg_web_root');
		
    $this->savetodebug('session store of input data key is ROGO_language');
    if (isset($_REQUEST['ROGO_language'])) {
      $_SESSION['ROGO_language'] = $_REQUEST['ROGO_language'];
			
			$language = LangUtils::getLang($cfg_web_root);
			$lang_path = "{$cfg_web_root}lang/$language/" . str_replace($cfg_web_root, '', $_SERVER['SCRIPT_FILENAME']);

			if (file_exists($lang_path)) {
				require $lang_path;
			}
    }

    return $sessionstoreobj;
  }
  
  function add_language_selection($display_std_form_obj) {
    global $string;
    $this->savetodebug('add_language_selection');
    
    $newfield = new displaystdformobjfield();
    $newfield->type = 'select';
    $newfield->description = '';
    $newfield->default = LangUtils::getLang($this->settings['cfg_web_root']);
    $newfield->name = 'ROGO_language';
    $newfield->options = isset($this->settings['available_languages']) ? $this->settings['available_languages'] : array('English'=>'en');
    $display_std_form_obj->fields[] = $newfield;

    return $display_std_form_obj;
  }
  
}
?>

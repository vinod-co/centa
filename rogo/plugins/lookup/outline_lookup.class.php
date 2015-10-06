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
 * The outline lookup class
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */
$configObject=Config::get_instance();

class outline_lookup {

  protected $name;
  protected $number;

  protected $form;
  protected $settings;
  protected $db;
  protected $calling_object;


  public $debug = array();
  public $debugpointer = 0;

  protected $error = NULL;
  public $rogoid = false;


  protected $lookupapiversion;
  protected $callbackarray;
  protected $impliments_api_lookup_version = 0;

  /**
   * @param $calling_object object its called from
   * @param $settings array settings options
   * @param $number int the number this is
   * @param $name string the name of it
   * @param $db object a lin kto db
   * @param $returndata object where data is stored
   * @param $form object a class with form data in
   */
  function __construct($number, $name, $lookupapiversion) {
    $this->lookupapiversion = $lookupapiversion;
    $this->name = $name;
    $this->number = $number;
  }

  function apicheck() {

    if ($this->lookupapiversion != $this->impliments_api_lookup_version) {
      $this->savetodebug('This lookup object is implementing an different version of the api than this plugin does');
      $this->set_error('Wrong API');

      return true;
    }

    return false;
  }

  function set_error($msg) {
    if (strlen($this->error) > 0) {
      $this->error .= '<br>';
    }
    $this->error .= $msg;
  }

  function init($object) {
    $this->db = new mysqli();
    $this->db = & $object->db;
    $this->calling_object = & $object->calling_object;
//    $this->returndata = & $object->returndata;
//    $this->retdata = & $this->returndata[$this->number];
    $this->form = & $object->form;
    $this->settings = & $object->settings;
    $this->session = & $object->calling_object->session;
    $this->request = & $object->calling_object->request;

  }


  function error_handling($context = null) {

    $context1 = array();
    if (is_null($context)) {
      // if no array set get currently define variables in this object
      $context = get_defined_vars($this);
    }

    $context1=error_handling($context);
    if (isset($context1['settings'])) {
      $context1['settings'] = 'hidden for security';
    }
    return $context1;
  }


//fake function used in mocking but if things go wrong have an outline here
  function mock($callingobject, $settings, $number, $name, $db, $returndata, $form) {
    return false;
  }


  /**
   * @param $debugmessage string the debug message to store
   */
  function savetodebug($debugmessage) {
    $this->debug[] = $debugmessage;
  }

  /**
   * @param $section string the section to get the callback from
   *
   * @return mixed
   */
  function get_callback($section) {
    return $this->calling_object->get_callback($section);
  }

  /**
   * @param $objid int the objectid
   *
   * @return mixed
   */
  /*  function get_new_debug_messages($objid) {
      return $this->returndata[$objid]->get_new_debug_messages();
    }*/
  function get_new_debug_messages($number = NULL) {
    if (is_null($number)) {
      $returnarray = array();
      while (isset($this->debug[$this->debugpointer])) {
        $returnarray[$this->debugpointer] = $this->debug[$this->debugpointer++];
      }

      return $returnarray;
    } else {
      return $this->calling_object->lookupPluginObj[$number]->get_new_debug_messages();
    }
  }

  /**
   * @param $objid int the objectid
   *
   * @return mixed
   */
  function get_module_lookupinfo($objid) {
    return $this->calling_object->lookupinfo[$objid];
  }


  function register_callback_sections() {
    //this is blank so that classes that dont register anything dont break
    return array();
  }

  /**
   * @param $callback callback routine
   * @param $section string section to register callback in
   * @param $number string the number this object is
   * @param $name string the name this object is
   * @param $insert bool to insert rather than append
   *
   * @return bool
   */
  function register_callback($callback, $section, $number, $name, $insert = false) {
    //return $this->calling_object->register_callback($callback, $section, $number, $name, $insert);
    $this->callbackarray[] = array($callback, $section, $number, $name, $insert);
  }


  /**
   *
   */
  function register_callback_routines() {
    //this is blank so that classes that dont register anything dont break
    return array();
  }

  /**
   * @param $setting string the setting to return or false if it doesnt exist
   *
   * @return mixed
   */
  function get_settings($setting) {
    if (!isset($this->settings[$setting])) {
      return false;
    }

    return $this->settings[$setting];
  }

  function get_info() {
    $data = new stdClass();
    $data->name = $this->name;
    $data->number = $this->number;
    $data->classname = get_class($this);
    $data->classname = substr($data->classname, 0, strpos($data->classname, '_auth'));
    $data->version = $this->version;
    $data->settings = $this->settings;
    $data->api_implimented = $this->impliments_api_lookup_version;
    $data->error = $this->error;
    if (isset($this->callbackarray)) {
      foreach ($this->callbackarray as $callback) {
        $funcname = $callback[0][1];
        $where = $callback[1];
        $data->callbackfunctions[] = array($funcname, $where);
      }
    }

    return $data;
  }
}



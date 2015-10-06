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
 * Handles UoN LTI Integration in Rogo
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2013 The University of Nottingham
 * @package
 */

require_once $configObject->get('cfg_web_root') . 'classes/userutils.class.php';
require_once $configObject->get('cfg_web_root') . 'classes/smsutils.class.php';

class lti_integration_extended extends lti_integration {

  public $description = 'UoN';

  static private $dept_code = array('MS' => 'Surgery', 'CC' => 'ACS', 'AA' => 'American & Canadian Studies', 'AC' => 'Archaeology', 'LA' => 'Urban Planning', 'AD' => 'Art History', 'MB' => 'Physiology & Pharmacology', 'ST' => 'Biosciences', 'AL' => 'CELE', 'EC' => 'Chemical Engineering', 'EN' => 'Mining Engineering', 'PC' => 'Chemistry', 'MC' => 'Public Health Medicine & Epidemiology', 'MG' => 'Obstetrics, Midwifery & Gynaecology', 'LI' => 'Trent Institute for Health Services Research', 'EV' => 'Structures', 'AB' => 'Classics', 'MR' => 'Pathology', 'PS' => 'Computer Science', 'LC' => 'Contemporary Chinese Studies', 'MZ' => 'Medicine', 'TT' => 'PGCE', 'AJ' => 'Critical Theory', 'RN' => 'Cultural Studies', 'LE' => 'Economics', 'EE' => 'Electrical & Electronic Engineering', 'EZ' => 'Engineering', 'IS' => 'Engineering Surveying & Space Geodesy', 'AE' => 'English', 'AR' => 'Modern Languages', 'EP' => 'Manufacturing Engineering & Operational Management', 'AF' => 'French', 'LQ' => 'Sociology', 'LG' => 'Geography', 'AG' => 'German', 'BR' => 'Training & Staff Development Unit', 'AS' => 'Portuguese', 'AH' => 'History', 'IT' => 'Information Technology', 'RH' => 'Institute of Hearing Research', 'NI' => 'Institute of Infections and Immunity', 'LW' => 'Institute of Work, Health & Organizations', 'OI' => 'International Office', 'UL' => 'Language Centre', 'LL' => 'Law', 'PL' => 'Life & Env Sciences', 'EM' => 'Materials Engineering & Materials Design', 'PM' => 'Theoretical Mechanics', 'EA' => 'Mechanical Engineering', 'AM' => 'Music', 'ZN' => 'Ningbo', 'SHS' => 'Nursing', 'PA' => 'Pharmacy', 'AP' => 'Philosophy', 'PP' => 'Physics', 'LD' => 'Politics', 'LP' => 'Psychology', 'AV' => 'Slavonic Studies', 'AT' => 'Theology', 'SV' => 'Vet School');

  //REMOVED as no longer used by lti as this is done at the authentication layer
  static function user_add($username, $password) {
  }

  static function user_time_check($time, $user = '') {
    $time1 = strtotime($time);
    $time2 = time();
    $timediff = $time2 - $time1;
    if ($timediff > (60 * 60 * 24 * 7 * 15)) {
      //if ($timediff > (60 * 60 * 1)) {
      return true;
    }

    return false;
  }

  static function allow_staff_edit_link() {
    return false;
  }

  static function allow_module_self_reg($data) {
    return true;
  }

  static function allow_staff_module_register($data) {
    return true;
  }

  static function allow_module_create($data) {
    return true;
  }

  static function sms_api($data) {
    global $mysqli;

    if ($data[0] != 'SMS') {
      return '';
    }
    $SMS = SmsUtils::GetSmsUtils();
    if ($SMS === false) {
      $configObject = Config::get_instance();
      $notice = UserNotices::get_instance();
      $userObject = UserObject::get_instance();

      $userid = 0;
      $username = 'PRE LOGIN';
      if (isset($userObject)) {
        $userid = $userObject->get_user_ID();
        $username = $userObject->get_username();
      }
			$error_type = 'Notice';
      $errstr = 'ROGO:SMS not correctly setup';
      $errfile = 'lti_integration.php';
      if (is_null($configObject->get('cfg_db_port'))) {
        $configObject->set('cfg_db_port', 3306);
      }
      // Query may fail if we try to insert while another statement is open.
      // Since we don't have a handle on the original statement, create another DB link
      $mysqli2 = DBUtils::get_mysqli_link($configObject->get('cfg_db_host'), $configObject->get('cfg_db_username'), $configObject->get('cfg_db_passwd'), $configObject->get('cfg_db_database'), $configObject->get('cfg_db_charset'), $notice, $configObject->get('dbclass'), $configObject->get('cfg_db_port'));

      $log_error = $mysqli2->prepare("INSERT INTO sys_errors VALUES(NULL, NOW(), ?, ?, ?, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, ?)");
      $log_error->bind_param('issssssssisss', $userid, $username, $error_type, $errstr, $errfile, $errline, $_SERVER['PHP_SELF'], $_SERVER['QUERY_STRING'], $_SERVER['REQUEST_METHOD'], $paperID, $post_data, $variables, $backtrace);
      $log_error->execute();
      $log_error->close();
			
      return '';
    } else {
      $SMS->set_module($data[2]);
			
      return $SMS->url;
    }

  }

  static function module_code_translated_store($data) {
    $return = '';
    foreach ($data as $k => $v) {
      if ($v[2] == '') {
        $v[2] = 'UK';
      }
      $module = $v[1];
      $replaced_module = str_replace('_UNMC', '', $module);
      $replaced_module = str_replace('_UNNC', '', $replaced_module);
      $v[1] = $replaced_module;

      $extra = '';
      if ($v[0] == 'Manual') {
        $extra = 'ZZ-';
      }
      $return = $return . '-' . $extra . $v[1] . '-' . $v[2];
    }
    $return = substr($return, 1);

    return $return;
  }

  static function module_code_translate($c_internal_id, $course_title = ' ') {

    if (stripos($c_internal_id, ' ') !== false) {
      self::invalid_module_code($c_internal_id, array(), 'initial blank check');
    }

    // only get the shortname through  (courseID is only probably accessible via specific moodle webservices api
    // shortname for real module try XXXXXX-YY-ZZZWWWW  WHERE XXXXXX is saturn code YY is country rest we dont care about.
    // shortname for non module VV-XXXXX-XXXXX-YY-WWWW WHERE XXXXXXXXXX is the fake 'module code'  YYY is country VV is DEPT 2 letter code
    // shortname for metamodules is XXXXXX-YY-XXXXXX-YY-XXXXXXX-YYY-ZZZWWWWW where the set of XXXXXX, YY are unknown
    // convert vle module format into rogo format

    $exploded = explode('-', $c_internal_id);
    $length = strlen($exploded[0]);
    $fin = strlen($course_title);

    if (strpos($course_title, '(') !== false) $fin = strpos($course_title, '(') - 1;
    $course_title = substr($course_title, 0, $fin);
    if ($length < 6) {
      //not saturn code
      $campus = '';
      //this should mean its a fake course
      $modcode = '';
      for ($a = 1; $a < count($exploded); $a++) {
        if (in_array(strtoupper($exploded[$a]), array('UK', 'MY', 'CN'))) {
          $campus = strtoupper($exploded[$a]);
          break;
        }
        $modcode = $modcode . '-' . $exploded[$a];
      }
      $modcode = substr($modcode, 1);
      $schoolname = 'UNKNOWN School';
      if (isset(self::$dept_code[$exploded[0]])) {
        $schoolname = self::$dept_code[$exploded[0]];
      }
      $selfreg = 1;
      if ($course_title == ' ') {
        $course_title = 'MISSING: ';
      }
      $data[] = array('Manual', $modcode, $campus, $schoolname, $selfreg, $course_title);


    } else {
      $a = 0;
      $b = 0;
      $data = array();
      $selfreg = 0;
      while (isset($exploded[$a])) {
        if (strlen($exploded[$a]) == 6) {
          //saturn codes are 6 chars
          // data is

          $data[$b++] = array('SMS', $exploded[$a], 'CampusMissing', 'UNKNOWN School', $selfreg, "MISSING:$course_title");
        } elseif (strlen($exploded[$a]) == 2) {
          // probably campus check
          if (in_array(strtoupper($exploded[$a]), array('UK', 'MY', 'CN'))) {
            for ($c = 0; $c < $b; $c++) {
              if ($data[$c][2] == 'CampusMissing') {
                $data[$c][2] = strtoupper($exploded[$a]);
              }
            }
          }
        }
        $a++;
      }
    }

    foreach ($data as $k => $v) {

      if (substr($v[5], 0, 8) == 'MISSING:' and $v[0] == 'SMS') {
        $sms = SmsUtils::GetSmsUtils();
        if ($sms === false) {
          $data[$k][5] = "SATURN " . $data[$k][5];
        } else {
          $sms->set_module($v[2]);
          $returned = $sms->get_module_info($v[1]);
          if ($returned !== false) {
            $data[$k][5] = $returned[1];
            $data[$k][3] = $returned[2];
          } else {
            $data[$k][5] = "SATURN " . $data[$k][5];
          }
        }
      }

      if ($data[$k][1] == '') {
        self::invalid_module_code($c_internal_id, $data, 'during loop');
      }

      if ($v[2] == 'MY') {
        $data[$k][1] = $data[$k][1] . '_UNMC';
      } elseif ($v[2] == 'CN') {
        $data[$k][1] = $data[$k][1] . '_UNNC';
      }

    }
    if (count($data) == 1 and substr($data[0][5], 0, 8) == 'MISSING:' and strlen($data[0][5]) > 9) {
      $data[0][5] = substr($data[0][5], 8);
    }
    // return the data


    // returning an array containing an array, description of inner array
    // first is 'Manual' or 'SMS' indicating if its not or it is a manual add or a live SMS based module
    // second is the module code
    // third is campus
    // fourth is School it belongs to as text
    // fifth is if its self registration module
    // sixth is the module title.  if it starts MISSING: then there is need for manual intervention to complete this correctly


    if (count($data) === 0) {
      self::invalid_module_code($c_internal_id, $data, 'no returned data');
    }

    return $data;
  }

  static function invalid_module_code($c_internal_id, $data, $location = '') {
		$notices = UserNotices::get_instance();
    $notices->display_notice("Module code error", 'There is a problem with the module code as the translation code has resulted in an error.  Please contact Learning Team Support <a href="mailto:learning-team-support@nottingham.ac.uk">learning-team-support@nottingham.ac.uk</a>  Please include this debug info below:', '/artwork/access_denied.png', '#C00000');

    echo '<p>Incoming Module Code: ' . $c_internal_id . '</p>';
    echo "<p>At: $location</p>";

    exit();
  }
}

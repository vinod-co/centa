<?php
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
 * Base object for questions
 *
 * @author Anthony Brown
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */


require_once('question.interface.php');
define('QUESTION_ERROR', -1);

define('Q_MARKING_EXACT', 1);															// Student answer is an exact match
define('Q_MARKING_FULL_TOL', 2);													// Student answer is within full marks tollerance
define('Q_MARKING_PART_TOL', 3);													// Student answer is within partial marks tollerance
define('Q_MARKING_PART_UNITS_WRONG', 4);									// Student answer has incorrect units
define('Q_MARKING_WRONG', 0);															// Student is marked as wrong
define('Q_MARKING_UNMARKED', -1);													// Student answer is unmarked
define('Q_MARKING_NOTANS', -2);														// Student has left question unanswered
define('Q_MARKING_ERROR', -3);														// Unspecified marking error
define('Q_MARKING_UNANSWERABLE', -4);											// It is imposible to answer the question (e.g. previous linked question not answered)
// Error section
define('Q_MARKING_UNCALC_ANSWER', -5);										// Error calculating what the correct answer should be
define('Q_MARKING_UNCALC_FULL_TOLLERANCE', -6);						// Error determining full tollerance figure
define('Q_MARKING_UNCALC_PARTIAL_TOLLERANCE', -7);				// Error determining partial tollerance figure
define('Q_MARKING_UNCALC_FORMAT', -8);										// Error with the formatting (dp and sf)
define('Q_MARKING_UNCALC_USER_ANSWER', -9);								// Error determining if the user answer is correct
define('Q_MARKING_UNCALC_DIST_FROM_ANSWER', -10);					// Error calculating the distance from the correct answer
define('Q_MARKING_UNCALC_WITHIN_FULL_TOLERANCE', -11);		// Error checking if the answer is within full tollerance range
define('Q_MARKING_UNCALC_WITHIN_PARTIAL_TOLERANCE', -12);	// Error checking if the answer is within partial tollerance range
define('Q_MARKING_UNCALC_STRICT_DP_CHECK', -13);					// Error checking the decimal places in the answer


Class Question {

  public $id = -1;
  protected $excluded = '';
  protected $type = null;
  protected $theme = '';
  protected $scenario = '';
  protected $scenario_plain = '';
  protected $leadin = '';
  protected $leadin_plain = '';
  protected $notes = '';
  protected $correct_fback = '';
  protected $incorrect_fback = '';
  protected $score_method = 'Mark per Option';
  protected $display_method = '';
  protected $option_order = 'display order';
  protected $standards_setting = '';
  protected $bloom = null;
  protected $owner_id = null;
  protected $media = '';
  protected $media_width = 0;
  protected $media_height = 0;
  protected $teams = array();
  protected $checkout_time = null;
  protected $checkout_author_id = '';
  protected $created = null;
  protected $last_edited = null;
  protected $locked = null;
  protected $deleted = null;
  protected $status = -1;
  protected $settings = '';
  public $options = array();

  // Below are for support in question display etc

  public $error;
  public $useranswer = null;

  public $markinfo = null;
  public $qmark = null;
  public $totalpos = null;


  public $q_media = '';
  public $q_media_height = '';
  public $q_media_width = '';

  public $uniqid;

  public $std;

  function set_settings($settings) {
    if (!is_array($settings)) {
      $this->settings = json_decode($settings, true);
    } else {
      $this->settings = $settings;
    }
  }

  function set_useranswer($useranswer) {
    if (!is_array($useranswer)) {
      $this->useranswer = json_decode($useranswer, true);
    } else {
      $this->useranswer = $useranswer;
    }
  }

  function add_to_useranswer($key, $value) {
    $this->useranswer[$key] = $value;
  }

  function export_save(&$array) {
    $classvar = get_object_vars($this);
    foreach ($classvar as $key => $value) {
      if ($key == 'useranswer') {
        $key = 'user_answer';
      } elseif ($key == 'options') {
        $key = 'DONOTUSE';
      }
      if ($key != 'DONOTUSE') {
        $array[$key] = $value;
      }
      if ($key == 'qmark') {
        $array['mark'] = $value;
      }
    }
    if (isset($this->settings['m_correct'])) {
      $array['marks_correct'] = $this->settings['m_correct'];
    }
    if (isset($this->settings['m_partial'])) {
      $array['marks_partial'] = $this->settings['m_partial'];
    }
    if (isset($this->settings['m_incorrect'])) {
      $array['marks_incorrect'] = $this->settings['m_incorrect'];
    }
  }

  function load($array) {
    foreach ($array as $key => $value) {
      if (property_exists($this, $key)) {
        $func_name = "set_" . $key;
        if (method_exists($this, $func_name)) {
          $this->$func_name($value);
        } else {
          $this->$key = $value;
        }
      }
      if ($key == 'q_id') {
        $this->id = $value;
      } elseif ($key == 'user_answer') {
        $this->set_useranswer($value);
      }
    }

    if (!is_array($this->options)) {
      // Convert to objects!
    }
  }
  
  function save($db) {
		if ($this->id > 0) {
			// Update the database.          
			$query = $db->prepare("UPDATE questions SET 
																								theme = ?, 
																								scenario = ?, 
																								leadin = ?, 
																								correct_fback = ?, 
																								incorrect_fback = ?, 
																								display_method = ?, 
																								notes = ?, 
																								q_media = ?, 
																								q_media_width = ?, 
																								q_media_height = ?, 
																								last_edited = ?, 
																								bloom = ?, 
																								scenario_plain = ?, 
																								leadin_plain = ?, 
																								std = ?,
																								status = ?, 
																								q_option_order = ?, 
																								score_method = ?, 
																								settings = ?  
																							WHERE 
																								q_id = ?
																					 ");
		
			if (is_array($this->settings)) {
				$settings = json_encode($this->settings);
			} else {
				$settings = $this->settings;
			}
      $this->last_edited = date('Y-m-d H:i:s');
			
			$query->bind_param('sssssssssssssssisssi',  $this->theme, $this->scenario, $this->leadin, $this->correct_fback, $this->incorrect_fback, $this->display_method, $this->notes, 
															$this->q_media, $this->q_media_width, $this->q_media_height, $this->last_edited, $this->bloom, $this->scenario_plain, $this->leadin_plain, 
															$this->standards_setting, $this->status, $this->option_order, $this->score_method, $settings, $this->id);
			$query->execute();
			$query->close();
		} else {
			//insert
			throw new Exception('Can not insert questions using this class (INSERT has not been implemented)');
		}
  }
}
?>

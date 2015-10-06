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
 * Class for True/False questions
 *
 * @author Rob Ingram
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */

Class QuestionTRUE_FALSE extends QuestionEdit {
  
  public $max_options = 1;
  protected $_answer_positive = 't';
  protected $_answer_negative = 'f';
  protected $display_method = 'horizontal';
  protected $_fields_change = array('option_correct', 'option_marks_correct', 'option_marks_incorrect');
  protected $_allow_change_marking_method = false;

  function __construct($mysqli, $userObj, $lang_strings, $data = null) {
    parent::__construct($mysqli, $userObj, $lang_strings, $data);
    
    $this->_fields_unified = array('marks_correct' => $this->_lang_strings['markscorrect'], 'marks_incorrect' => $this->_lang_strings['marksincorrect']);
    $this->_display_methods = array('vertical' => $this->_lang_strings['vertical'], 'horizontal' => $this->_lang_strings['horizontal'], 'dropdown' => $this->_lang_strings['dropdownlist']);
    
    // 'correct' is not a unified field for True/False questions
    $this->_fields_editable[] = 'correct';
  }

  /**
   * Get the labels for true/false options. These change depending on the score method
   */
  public function get_tf_labels() {
    if (substr($this->get_display_method(), 0, 2) == 'YN') {
      $labels = array('true' => mb_substr($this->_lang_strings['yes'],0,1), 'false' => mb_substr($this->_lang_strings['no'],0,1));
    } else {
      $labels = array('true' => mb_substr($this->_lang_strings['true'],0,1), 'false' => mb_substr($this->_lang_strings['false'],0,1));
    }
    
    return $labels;
  }
}
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
 * Class for Correction behaviour for Multiple Choice questions
 *
 * @author Rob Ingram
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */

include_once 'Corrector.class.php';

class MCQCorrector extends Corrector {
  /**
   * Change the correct answer after the question has been locked. Update user marks in summative log table
   * @param integer $new_correct new correct answer
   * @param integer $paper_id
   */
  public function execute($new_correct, $paper_id, &$changes, $paper_type) {
    $errors = array();

    $first = reset($this->_question->options);
    $old_correct = $first->get_correct();
    $totalpos = $first->get_marks_correct();

    if ($new_correct['option_correct'] != $old_correct) {
      foreach ($this->_question->options as $option) {
        $option->set_correct($new_correct['option_correct']);
      }

      $this->_question->add_unified_field_modification('correct', $this->_lang_strings['correctanswer'], $old_correct, $new_correct['option_correct'], $this->_lang_strings['postexamchange']);
      $changes = true;
    }

    if ($changes) {
      try {
    	  if (!$this->_question->save()) {
    	    $errors[] = $this->_lang_strings['datasaveerror'];
    	  } else {
          // Remark the student's answers in 'log{$paper_type}'.
          $result = $this->_mysqli->prepare("SELECT l.user_answer, l.id FROM log{$paper_type} l INNER JOIN log_metadata lm ON l.metadataID = lm.id WHERE l.q_id = ? AND lm.paperID = ?");
          $result->bind_param('ii', $this->_question->id, $paper_id);
          $result->execute();
          $result->store_result();
          $result->bind_result($user_answer, $id);
          while ($result->fetch()) {
            $new_mark = ($user_answer == $new_correct['option_correct']) ? $first->get_marks_correct() : $first->get_marks_incorrect();
            $updateLog = $this->_mysqli->prepare("UPDATE log{$paper_type} SET mark = ?, totalpos = ? WHERE id = ?");
            $updateLog->bind_param('iii', $new_mark, $totalpos, $id);
            $updateLog->execute();
            $updateLog->close();
          }
          $result->free_result();
          $result->close();
    	  }
    	} catch (ValidationException $vex) {
    	  $errors[] = $vex->getMessage();
    	}

      if (count($errors) == 0) {
        $this->invalidate_paper_cache($paper_id);
      }
    }

    return $errors;
  }


}

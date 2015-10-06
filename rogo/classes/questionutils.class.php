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
* Utility class for question related functions
*
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/


Class QuestionUtils {

  /**
   * Does a given Question ID exist in the question bank.
   * @param integer $q_id the question ID to be searched for.
   * @param resource $db the database connection.
   * @return string The leadin
   */
  static function question_exists($q_id, $db) {
    $stmt = $db->prepare("SELECT q_id FROM questions WHERE q_id = ? AND deleted IS NULL LIMIT 1");
    $stmt->bind_param('i', $q_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tmp_q_id);
    $stmt->fetch();
    $exists = ($stmt->num_rows == 0) ? false : true;
    $stmt->close();

    return $exists;
  }

  /**
   * Does a given Question ID exist on a specific paper.
   * @param integer $q_id the question ID to be searched for.
   * @param integer $paperID the paper ID to be searched for.
   * @param resource $db the database connection.
   * @return string The leadin
   */
  static function question_exists_on_paper($q_id, $paperID, $db) {
    $stmt = $db->prepare("SELECT q_id FROM questions, papers WHERE papers.question = questions.q_id AND paper = ? AND q_id = ? LIMIT 1");
    $stmt->bind_param('ii', $paperID, $q_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tmp_q_id);
    $stmt->fetch();
    $exists = ($stmt->num_rows == 0) ? false : true;
    $stmt->close();

    return $exists;
  }

  /**
   * Get the owner ID for a particular question.
   * @param integer $q_id the question ID to be looked up.
   * @param resource $db the database connection.
   * @return string The leadin
   */
  static function get_ownerID($q_id, $db) {
    $stmt = $db->prepare("SELECT ownerID FROM questions WHERE q_id = ? LIMIT 1");
    $stmt->bind_param('i', $q_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ownerID);
    $stmt->fetch();
    $stmt->close();

    return $ownerID;
  }

  /**
   * Get the leading for a give question ID
   * @param integer $q_id the question ID to be looked up.
   * @param resource $db the database connection.
   * @return string The leadin
   */
  static function get_leadin($q_id, $db) {
    $stmt = $db->prepare("SELECT leadin FROM questions WHERE q_id = ? LIMIT 1");
    $stmt->bind_param('i', $q_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($leadin);
    $stmt->fetch();
    $leadin = ($stmt->num_rows == 0) ? '' : $leadin;
    $stmt->close();

    return $leadin;
  }

  /**
   * Strip tags from the leading string (if it doesn't contain equations) and trim length
   * @param $leadin
   * @return string
   */
  static function clean_leadin($leadin) {
    if (strpos($leadin, 'class="mee"') === false AND strpos($leadin, 'class=mee') === false) {
      $leadin = strip_tags($leadin);                                     // No equation, strip all tags
      if (mb_strlen($leadin) > 160) {
        $leadin = mb_substr($leadin, 0, 160) . '...';
      }
    } else {
      $leadin = trim(str_replace('&nbsp;',' ', $leadin));
    }
    $leadin = trim($leadin);

    return $leadin;
  }

  /**
   * returns an array of id/keywords that the question is on
   * @param intager $q_id the id of the questions
   * @param resource $db the database connection.
   * @return array of keywords
   */
  static function get_keywords($q_id, $db) {
    $keywords = array();

    $stmt = $db->prepare("SELECT keywordID, keyword FROM keywords_question, keywords_user WHERE q_id = ? and keywords_question.keywordID = keywords_user.id");
    $stmt->bind_param('i', $q_id);
    $stmt->execute();
    $stmt->bind_result($keywordID, $keyword);
    while($res = $stmt->fetch()) {
      $keywords[$keywordID] = $keyword;
    }
    $stmt->close();

    return $keywords;
  }

  /**
   * returns an array of question IDs/module IDs
   * @param array $q_ids list of questions to check
   * @param resource $db the database connection.
   * @return array of modules keyed on q_id
   */
  static function multi_get_modules($q_ids, $db) {
    $modules = array();

    $stmt = $db->prepare("SELECT q_id, idMod FROM questions_modules WHERE q_id IN (" . implode(',', $q_ids) . ")");
    $stmt->execute();
    $stmt->bind_result($q_id, $idMod);
    while($res = $stmt->fetch()) {
      $modules[$q_id][$idMod] = $idMod;
    }
    $stmt->close();

    return $modules;
  }

  /**
   * returns an array of modules/teams that the question is on
   * @param integer $q_id the id of the questions
   * @param resource $db the database connection.
   * @return array of modules keyed on idMod
   */
  static function get_modules($q_id, $db) {
    $modules = array();

    $stmt = $db->prepare("SELECT idMod, moduleID FROM questions_modules, modules WHERE q_id = ? AND questions_modules.idMod = modules.id");
    $stmt->bind_param('i', $q_id);
    $stmt->execute();
    $stmt->bind_result($idMod, $moduleID);
    while($res = $stmt->fetch()) {
      $modules[$idMod] = $moduleID;
    }
    $stmt->close();

    return $modules;
  }

  /**
  * Update the modules for a question bast on the modules that the papers it is part of are on
  * @param integer $q_id the id of the questions.
	* @param resource $db the database connection.
  * @return void
  */
  static function update_modules_from_papers($q_id, $db) {

    $sql = <<<SQL
      SELECT DISTINCT idMod
      FROM papers, properties, properties_modules
      WHERE properties.property_id = properties_modules.property_id
      AND properties.property_id = paper
      AND question = ?
      AND deleted is NULL
SQL;
    $update = $db->prepare($sql);
    $update->bind_param('i', $q_id);
    $update->execute();
    $update->bind_result($tmp_idMod);
    $on_idMod = array();
    while($update->fetch()) {
      $on_idMod[$tmp_idMod] = $tmp_idMod;
    }
    $update->close();

    // Questions may be on modules the current users is not in - should we exclude these from the delete
    $update = $db->prepare("DELETE FROM questions_modules WHERE q_id = ?");
    $update->bind_param('i', $q_id);
    $update->execute();
    $update->close();

    QuestionUtils::add_modules($on_idMod, $q_id, $db);

  }

  /**
  * Updates the modules on a question removes modules if the user has permission to do so and then adds in the new modules
  * @param $modules an array of modules keyed on idMod
  * @param $q_id the id of the question
	* @param resource $db the database connection.
	* @param object $userObj the currently authenticated user object.
  * @return void
  */
  static function update_modules($modules, $q_id, $db, $userObj) {
    $user_can_delete = '';
    if (!$userObj->has_role('SysAdmin')) {    // If SysAdmin no restrictions in deleting.
      $staff_modules = $userObj->get_staff_modules();
      if (count($staff_modules) > 0) {
        $user_can_delete = "AND idMod IN (" . implode(',', array_keys($staff_modules)) . ")"; //users can only remove modules if they are on the team
      }
    }

    $editProperties = $db->prepare("DELETE FROM questions_modules WHERE q_id = ? $user_can_delete");
    $editProperties->bind_param('i', $q_id);
    $editProperties->execute();
    $editProperties->close();

    QuestionUtils::add_modules($modules, $q_id, $db);
  }

  /**
  * Add modules to a question ignoring any duplicates
  * @param $modules an array of modules keyed on idMod
  * @param $q_id the id of the question
	* @param resource $db the database connection.
  * @return void
  */
  static function add_modules($modules, $q_id, $db) {
    $update = $db->prepare("INSERT INTO questions_modules VALUES(?, ?) ON DUPLICATE KEY UPDATE idMod = idMod");
    foreach ($modules as $idMod => $ModuleID) {
      $update->bind_param('ii', $q_id, $idMod);
      $update->execute();
    }
    $update->close();
  }

  /**
  * add keywords to a question
  * @param $keywords an array of keywords keyed on IDs
  * @param $q_id the id of the question
	* @param resource $db the database connection.
  * @return void
  */
  static function add_keywords($keywords, $q_id, $db) {
    $update = $db->prepare("INSERT INTO keywords_question VALUES (?, ?)");
    foreach ($keywords as $keywordID => $keyword) {
      $update->bind_param('ii', $q_id, $keywordID);
      $update->execute();
    }
    $update->close();
  }

  /**
  * remove a module from a question
  * @param $idMod an array of modules to remove keyed on idMod
  * @param $q_id the id of the question or property_id
	* @param resource $db the database connection.
  * @return void
  */
  static function remove_modules($modules, $q_id, $db) {
    $update = $db->prepare("DELETE FROM questions_modules WHERE q_id = ? AND idMod = ?");
    foreach ($modules as $idMod => $ModuleID) {
      $update->bind_param('ii', $q_id, $idMod);
      $update->execute();
    }
    $update->close();
  }

/**
  * remove a question from rogo
  * Normal Questions - sets the deleted field we don't actuality delete the row form the questions table
  * Random Questions - deletes the rows in optionsto ensure random questions cannot use the deleted question
  * @param $q_id the id of the question or property_id
	* @param resource $db the database connection.
  * @return void
  */
  static function delete_question($q_id, $db) {
    $delete = $db->prepare("UPDATE questions SET deleted = NOW() WHERE q_id = ?");
    $delete->bind_param('i', $q_id);
    $delete->execute();
    $delete->close();

    $select_random = $db->prepare("SELECT o.o_id, o.option_text FROM questions q, options o WHERE q.q_id = o.o_id AND q_type = 'random' AND o.option_text = ?");
    $select_random->bind_param('s', $q_id);
    $select_random->execute();
    $select_random->store_result();
    $select_random->bind_result($o_id, $option_text);
    while ($select_random->fetch()) {
      $delete_random = $db->prepare("DELETE FROM options where o_id = ? AND option_text = ?");
      $delete_random->bind_param('is', $o_id, $option_text);
      $delete_random->execute();
      $delete_random->close();
    }
    $select_random->close();
  }

  static function lock_question($q_id, $db) {
    $lock = $db->prepare("UPDATE questions SET locked = NOW() WHERE q_id = ? AND locked IS NULL");
    $lock->bind_param('i', $q_id);
    $lock->execute();
    $lock->close();
  }

  /**
   * Get the number of questions assigned to a given status
   * @param  integer $status_id Status ID
   * @param  mysqli $db        DB link
   * @return integer           Number of questions assigned to the status
   */
  static function get_question_count_by_status($status_id, $db) {
    $query = $db->prepare("SELECT count(q_id) FROM questions WHERE status = ?");
    $query->bind_param('i', $status_id);
    $query->execute();
    $query->bind_result($count);
    $query->fetch();
    $query->close();

    return $count;
  }

  /**
   * Function to get available options text for question
   *
   * @param int $qid question identifier
   * @param mysqli $db
   * @return array option_text for supplied option
   */
  static function get_options_text($qid, $db) {
    $options = $db->prepare("SELECT option_text FROM options WHERE o_id = ?");
    $options->bind_param('i', $qid);
    $options->execute();
    $options->store_result();
    $options->bind_result($optionstext);
    $optionsarray = array();
    while ($options->fetch()) {
        $optionsarray[] = $optionstext;
    }
    $options->close();
    return $optionsarray;
  }

  /**
   * Function to get type of question
   *
   * @param int $qid question identifier
   * @param mysqli $db
   * @return string question type
   */
  static function get_question_type($qid, $db) {
    $type = $db->prepare("SELECT q_type FROM questions WHERE q_id = ?");
    $type->bind_param('i', $qid);
    $type->execute();
    $type->bind_result($qtype);
    $type->fetch();
    $type->close();
    return $qtype;
  }

  /**
    * Based on the parent random block id get the possible calculation questions.
    *
    * @param int $q_id question
    * @param mysqli $db
    * @return array $possible list of possible calculation questions
    */
   static function get_random_calc_question($q_id, $db) {
       $possible = array();
       $random = $db->prepare("SELECT q_id FROM questions WHERE q_type ='enhancedcalc' AND q_id in ("
           . "SELECT option_text FROM questions, options WHERE q_id = o_id AND q_type ='random' AND q_id = ?)");
       $random->bind_param('i', $q_id);
       $random->execute();
       $random->bind_result($random_id);
       while ($random->fetch()) {
           $possible[] = $random_id;
       }
       $random->close();
       return $possible;
   }

   /**
    * Based on the parent keyword block id get the possible calculation questions.
    *
    * @param int $q_id question
    * @param mysqli $db
    * @return array $possible list of possible calculation questions
    */
   static function get_keyword_calc_question($q_id, $db) {
       $possible = array();
       $random = $db->prepare("SELECT q_id FROM questions WHERE q_type ='enhancedcalc' AND q_id in ("
           . "SELECT q_id FROM keywords_question, options WHERE keywordID = option_text AND o_id = ?)");
       $random->bind_param('i', $q_id);
       $random->execute();
       $random->bind_result($random_id);
       while ($random->fetch()) {
           $possible[] = $random_id;
       }
       $random->close();
       return $possible;
   }
  
}
?>
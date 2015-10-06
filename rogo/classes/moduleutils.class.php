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

require_once $cfg_web_root . 'classes/rogostaticsingleton.class.php';
require_once $cfg_web_root . 'classes/schoolutils.class.php';
require_once $cfg_web_root . 'classes/logger.class.php';
require_once $cfg_web_root . 'classes/userobject.class.php';

/**
 *
 * Wrapper class for old static style calls to module_utils::[Function]
 *
 * @author Anthony Brown
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */
Class module_utils extends RogoStaticSingleton {
  public static $inst = NULL;
  public static $class_name = 'module'; //name of the new dynamic class

  /**
  * constructor
  */
  private function __construct() {}

}

/**
 *
 * Utility class for module related functionality.
 *
 * @author Anthony Brown
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */
Class module {

  /**
  * constructor
  */
  public function __construct() {}
  
  /**
   * Gets a list of staff on a modules' team.
   * 
   * @param integer $idMod - The ID of the module to use.
   * @param object $db     - MySQLi database connection.
   * @return array - List of staff on the module.
   */
  public function get_staff_members($idMod, $db) {
    $members = array();
    
    $result = $db->prepare("SELECT DISTINCT surname, initials, title, users.id FROM (modules_staff, users) WHERE modules_staff.memberID = users.id AND idMod = ? AND user_deleted IS NULL ORDER BY surname, initials");
    $result->bind_param('i', $idMod);
    $result->execute();
    $result->store_result();
    $result->bind_result($surname, $initials, $title, $userID);
    while ($result->fetch()) {
      $title = str_replace('Professor', 'Prof', $title);
      $members[] = array('surname'=>$surname, 'initials'=>$initials, 'title'=>$title, 'userID'=>$userID);
    }
    $result->close();
    
    return $members;
  }

  /**
   * Gets a list of students on a module.
   * 
   * @param string $calendar_year - Which academic session to use.
   * @param integer $idMod        - The ID of the module to use.
   * @param object $db            - MySQLi database connection.
   * @return array - List of students on the module.
   */
  public function get_student_members($calendar_year, $idMod, $db) {
    $members = array();
    
    $result = $db->prepare("SELECT DISTINCT surname, initials, title, users.id FROM (modules_student, users) WHERE modules_student.userID = users.id AND calendar_year = ? AND idMod = ? ORDER BY surname, initials");
    $result->bind_param('si', $calendar_year, $idMod);
    $result->execute();
    $result->store_result();
    $result->bind_result($surname, $initials, $title, $userID);
    while ($result->fetch()) {
      $members[] = array('surname'=>$surname, 'initials'=>$initials, 'title'=>$title, 'userID'=>$userID);
    }
    $result->close();
    
    return $members;
  }

  /**
   * Creates a new module.
   * 
   * @param integer $moduleid           - The code of the module.
   * @param string $fullname            - The full name of the module.
   * @param integer $active             - Is the module active or inactive.
   * @param integer $schoolID           - Which school the module belongs to.
   * @param string $vle_api             - Which curriculum map or VLE to use for learning objectives.
   * @param string $sms_api             - Which SMS system to link to.
   * @param integer $selfEnroll         - Can students self-enrol in the module.
   * @param bool $peer                  - Is Peer Review turned on.
   * @param bool $external              - Is External Examiner turned on.
   * @param bool $stdset                - Is Standard Setting turned on.
   * @param bool $mapping               - Is mapping turned on. 
   * @param integer $neg_marking        - Can negative marking be used in questions.
   * @param string $ebel_grid_template  - Which Ebel grid to assign (optional).
   * @param object $db                  - MySQLi database connection.
   * @param integer $sms_import         - 
   * @param integer $timed_exams        - Are timed summative exams allowed.
   * @param integer $exam_q_feedback    - Is question-based feedback allowed for summative exams.
   * @param integer $add_team_members   - Are team members allowed to add others.
   * @param integer $map_level          - What level to link to in the curriculum map.
   * @param string $academic_year_start - Day the module changes academic year.
   * 
   * @return boolean - True if module successfully added.
   */
  public function add_modules($moduleid, $fullname, $active, $schoolID, $vle_api, $sms_api, $selfEnroll, $peer, $external, $stdset, $mapping, $neg_marking, $ebel_grid_template, $db, $sms_import = 0, $timed_exams = 0, $exam_q_feedback = 1, $add_team_members = 1, $map_level = 0, $academic_year_start = '07/01') {

    // Return false if missing madatory fields. schoolid is actually a number
    if ($moduleid == '' or $fullname == '' or $schoolID === '') {
      return false;
    }

    // Don't create a duplicate module with the same module ID.
    if (module_utils::module_exists($moduleid, $db) !== false) {
      return false;
    }

    $checklist = '';
    if ($peer == true) $checklist .= ',peer';
    if ($external == true) $checklist .= ',external';
    if ($stdset == true) $checklist .= ',stdset';
    if ($mapping == true) $checklist .= ',mapping';
    if ($checklist != '') {
      $tmp_checklist = substr($checklist, 1);
    }

    $result = $db->prepare("INSERT INTO modules VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, ?, ?, ?, ?)");
    $result->bind_param('ssisssiiiiiiiis', $moduleid, $fullname, $active, $vle_api, $tmp_checklist, $sms_api, $selfEnroll, $schoolID, $neg_marking, $ebel_grid_template, $timed_exams, $exam_q_feedback, $add_team_members, $map_level, $academic_year_start);
    $result->execute();
    $result->close();
    if ($db->errno != 0) {
      return "failed at insert";
      return false;
    }

    $idMod = $db->insert_id;

    if ($sms_import == 1 and $sms_api != '') {
      $SMS = SmsUtils::GetSmsUtils();
      $SMS->update_module_enrolement($moduleid, $idMod, $sms_api, $db);
    }

    return $idMod;
  }
  
  /**
   * Update any part of a modules DB record.
   * 
   * @param integer $orig_moduleid  - the code of the module to update
   * @param type $updateData        - an array of key value pairs to update e.g 'fullname'=>'New full Name'
   * @param object $db              - MySQLi database connection.
   * @return boolean
   */
  public function update_module_by_code($orig_moduleid, $updateData, $db) {
    global $string;
    
    if ($orig_moduleid == '') {
      return false;
    }
    
    $orig_modinfo = $modinfo = module_utils::get_full_details_by_name($orig_moduleid, $db);
    
    if ($modinfo === false) {
      // The module must exist to update it!
      return false;
    }
    
    $orig_school_name = $modinfo['school'];
    $orig_school_id = $modinfo['schoolid'];
    
    $changed = false; 
    foreach ($updateData as $key => $val) {
      $key = strtolower($key);
      if ($key == 'idmod') {
        //never change the id :-)
        continue;
      }
      if ($modinfo[$key] != $val) {
        $modinfo[$key] = $val;
        $changed = true;
      }
    }
    
    if (!$changed) {
      // Nothing has changed return
      return true;
    }
    
    // Check mandatory fields
    if ($modinfo['moduleid'] == '' and $modinfo['fullname'] == '') {
      return false;
    }
    
    if ($orig_school_name != $modinfo['school']) {
      // We have updated the school so we need to get the new id from the schools table
      if ($orig_school_id != $modinfo['schoolid']) {
        // Do nothing as the id has already been updated
      } else {
        // Lookup the schoolID 
        $modinfo['schoolid'] = SchoolUtils::get_school_id_by_name($modinfo['school'], $db);
        if ($modinfo['schoolid'] === false) {
          // School not found ERROR
          return false;
        }
      }
    }
    
    $sql = "UPDATE modules SET 
               moduleid = ?,
               fullname = ?,
               active = ?, 
               vle_api = ?, 
               checklist = ?, 
               sms = ?, 
               selfenroll = ?, 
               schoolid = ?, 
               neg_marking = ?, 
               ebel_grid_template = ?, 
               timed_exams = ?, 
               exam_q_feedback = ?, 
               add_team_members = ?,
               map_level = ?,
               academic_year_start = ?
            WHERE 
              id = ?
            LIMIT 1
            ";
    
    $result = $db->prepare($sql);
    $result->bind_param('ssisssiiiiiiiisi', $modinfo['moduleid'], $modinfo['fullname'], $modinfo['active'], $modinfo['vle_api'], 
                                        $modinfo['checklist'], $modinfo['sms'], $modinfo['selfenroll'], $modinfo['schoolid'], 
                                        $modinfo['neg_marking'], $modinfo['ebel_grid_template'], $modinfo['timed_exams'], 
                                        $modinfo['exam_q_feedback'], $modinfo['add_team_members'], $modinfo['map_level'],
                                        $modinfo['academic_year_start'], $modinfo['idMod']);
    $res = $result->execute();
    
    // An array to convert DB fields to lang strings argghhh!!!!
    $lang_mappings = array(
                        'moduleid' => 'moduleid',
                        'fullname' => 'name',
                        'schoolid' => 'school',
                        'active' => 'active',
                        'vle_api' => 'objapi',
                        'checklist' => 'summativechecklist',
                        'sms' => 'smsapi',
                        'selfenroll' => 'allowselfenrol',
                        'neg_marking' => 'negativemarking',
                        'ebel_grid_template' => 'ebelgrid',
                        'timed_exams' => 'timedexams',
                        'exam_q_feedback' => 'questionbasedfeedback',
                        'add_team_members' => 'addteammembers',
                        'map_level' => 'map_level',
                        'academic_year_start' => 'academicyearstart',
                        );
    
    if ($res === true ) {
      // Log any changes
      $logger = new Logger($db);
      $userObject = UserObject::get_instance();
      foreach ($modinfo as $key => $val) {
        $key = strtolower($key);
        if ($key == 'idmod') {
          continue;
        }
        if ($orig_modinfo[$key] != $val) {
           
          $logger->track_change( 'Module', 
                                  $modinfo['idMod'], 
                                  $userObject->get_user_ID(), 
                                  $orig_modinfo[$key], 
                                  $modinfo[$key], 
                                  $string[$lang_mappings[$key]]
                               );
        }
      }
    }
    
    return true;
  }

  /**
   * Check if a module with the given code already exists
   * @param string $moduleid - The Module ID (code) for the module
   * @param object $db       - Database link class
   * @return boolean - True if there is already a module with the code
   */
  public function module_exists($moduleid, $db) {
    if ($moduleid == '') {  // No ID, don't bother to check the database.
      return false;
    }

    // Check for unique moduleID
    $exists = true;

    $result = $db->prepare("SELECT moduleid FROM modules WHERE moduleid = ? AND mod_deleted IS NULL");
    $result->bind_param('s', $moduleid);
    $result->execute();
    $result->store_result();
    $result->bind_result($tmp_moduleid);
    $result->fetch();
    if ($result->num_rows == 0) {
      $exists = false;
    }
    $result->free_result();
    $result->close();

    return $exists;
  }

  /**
   * Get the full details of a module given its module code
   * @param string $modID - The Module ID (code) for the module
   * @param object $db    - Database link class
   * @return array - Associative array containing the details of the module
   */
  public function get_full_details_by_name($modID, $db) {
    $moduleid = self::get_idMod($modID, $db);
    if ($moduleid === false) {
      return false;
    }

    return self::get_full_details_by_ID($moduleid, $db);
  }

  /**
   * Get the full details of a module given its ID
   * @param integer $modID - Database ID of the module
   * @param object $db     - Database link class
   * @return array e.g  'idMod' => int 291
   *                     'moduleid' => string '001' (length=3)
   *                     'fullname' => string 'This is a test module 22' (length=24)
   *                     'school' => string 'Training' (length=8)
   *                     'active' => int 1
   *                     'vle_api' => string '' (length=0)
   *                     'checklist' => string '' (length=0)
   *                     'sms' => string '' (length=0)
   *                     'selfenroll' => int 0
   *                     'schoolid' => int 42
   *                     'neg_marking' => int 1
   *                     'ebel_grid_template' => int 0
   *                     'timed_exams' => int 0
   *                     'exam_q_feedback' => int 1
   *                     'add_team_members' => int 1
   *                     'map_level ' => int 1
   */
  public function get_full_details_by_ID($modID, $db) {
    // returns false if not self enrol else returns needed data;
    $result = $db->prepare("SELECT 
                              modules.id,
                              moduleid, 
                              fullname, 
                              school, 
                              active, 
                              vle_api,
                              checklist, 
                              sms,
                              selfenroll, 
                              schoolid,
                              neg_marking,
                              ebel_grid_template,
                              timed_exams, 
                              exam_q_feedback, 
                              add_team_members,
                              map_level,
                              academic_year_start
                            FROM 
                              modules
                            LEFT JOIN
                              schools 
                            ON 
                               modules.schoolid = schools.id
                            WHERE
                               modules.id = ? AND 
                               mod_deleted IS NULL
                            ");
    if ($db->error) {
      try {
        throw new Exception("MySQL error $db->error", $db->errno);
      }
      catch (Exception $e) {
        echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
        echo nl2br($e->getTraceAsString());
      }
    }
    $result->bind_param('i', $modID);
    $result->execute();
    $result->store_result();
    $result->bind_result($idMod, $moduleid, $fullname, $school, $active, $vle_api, $checklist, $sms, $selfenroll, $schoolid, $neg_marking, $ebel_grid_template, $timed_exams, $exam_q_feedback, $add_team_members, $map_level, $academic_year_start);
    
    $result->fetch();
    if ($result->num_rows == 0) {
      $result->close();
      return false;
    }
    $result->close();
    
    return array( 'idMod' => $idMod,
                  'moduleid' => $moduleid,
                  'fullname' => $fullname, 
                  'school' => $school,
                  'active' => $active,
                  'vle_api' => $vle_api, 
                  'checklist' => $checklist,
                  'sms' => $sms,
                  'selfenroll' => $selfenroll, 
                  'schoolid' => $schoolid,
                  'neg_marking' => $neg_marking, 
                  'ebel_grid_template' => $ebel_grid_template,
                  'timed_exams' => $timed_exams, 
                  'exam_q_feedback' => $exam_q_feedback,
                  'add_team_members' => $add_team_members,
                  'map_level' => $map_level,
                  'academic_year_start' => $academic_year_start);
  }

  /**
   * Check if the module with the given ID is set to allow team members to add other members of staff to the team
   * @param string $modID - Module code of the module
   * @param object $db    - Database link class
   * @return boolean - Can team members add others to the team
   */
  public function is_allowed_add_team_members_by_name($modID, $db) {
    $moduleid = self::get_idMod($modID, $db);
    if ($moduleid === false) {
      return false;
    }

    return self::is_allowed_add_team_members_by_id($moduleid, $db);
  }

  /**
   * Check if the module with the given ID is set to allow team members to add other members of staff to the team
   * @param integer $modID - Database ID of the module
   * @param object $db     - Database link class
   * @return boolean - Can team members add others to the team
   */
  public function is_allowed_add_team_members_by_id($modID, $db) {
    $data = self::get_full_details_by_ID($modID, $db);
    if ($data === false) {
      return false;
    }
    if ($data['add_team_members'] == 0) {
      return false;
    }

    return true;
  }

  /**
   * The Module ID (code) of a module given its database ID
   * @param integer $modID - Database ID of the module
   * @param object $db     - Database link object
   * @return string  -  Module ID (code) of the module or false if not found
   */
  public function get_moduleid_from_id($modID, $db) {
    $modID = intval($modID);

    if ($modID === 0) {
      $moduleid = 'Unassigned';
    } else {
      $result = $db->prepare("SELECT moduleid FROM modules WHERE id = ? AND mod_deleted IS NULL");
      $result->bind_param('i', $modID);
      $result->execute();
      $result->store_result();
      $result->bind_result($moduleid);
      $result->fetch();
      if ($result->num_rows == 0) {
        $result->close();
        return false;
      }
      $result->close();
    }

    return $moduleid;
  }

  /**
   * The database ID of a module given its Module ID (code)
   * @param  string  $module_id Module ID (code) of the module
   * @param  mysqli  $db        Database link object
   * @return string             Database ID of the module or false if not found
   */
  public function get_idMod($module_id, $db) {
    if (is_array($module_id)) {
      $ids = array();

      $sql = implode("','", $module_id);
      $sql = str_replace("',' ", "','", $sql);

      $result = $db->prepare("SELECT id FROM modules WHERE moduleid IN ('$sql') AND mod_deleted IS NULL");
      $result->execute();
      $result->store_result();
      $result->bind_result($id);
      while ($result->fetch()) {
        $ids[] = $id;
      }
      $result->close();

      if (count($ids) == 0) {
        return false;
      }
      return $ids;
    } else {
      $result = $db->prepare("SELECT id FROM modules WHERE moduleid = ? AND mod_deleted IS NULL");
      $result->bind_param('s', $module_id);
      $result->execute();
      $result->store_result();
      $result->bind_result($id);
      $result->fetch();
      if ($result->num_rows == 0) {
        $result->close();
        return false;
      }
      $result->close();
      return $id;
    }
  }

  /**
   * Get a complete list of the Module ID (code) and title of modules indexed by database ID
   * @param  mysqli $db Database link object
   * @return array      Array of module details indexed by ID
   */
  public function get_module_list_by_id($db) {
    $modules = array();

    $result = $db->prepare("SELECT id, moduleid, fullname FROM modules WHERE mod_deleted IS NULL");
    $result->execute();
    $result->store_result();
    $result->bind_result($id, $moduleid, $fullname);
    while ($result->fetch()) {
      $modules[$id]['code'] = $moduleid;
      $modules[$id]['name'] = $fullname;
    }
    $result->close();

    return $modules;
  }

  /**
   * Set the deleted date for the module identified by database ID
   * @param  integer $idMod Database ID of the module to delete
   * @param  mysqli  $db    Database link object
   */
  public function delete_module($idMod, $db) {
    if ($idMod == '') {
      return false;
    }

    $result = $db->prepare("UPDATE modules SET mod_deleted = NOW() WHERE id = ?");
    $result->bind_param('i', $idMod);
    $result->execute();
    $result->close();
  }

  /**
   * Check if a list of modules allow timing. ALL of the given modules must be set to allow timing for this to be true
   * @param  array  $module_ids List of module database IDs
   * @param  mysqli $db         Database link object
   * @return boolean            True if all modules are set to allow timed exams
   */
  public function modules_allow_timing($module_ids, $db) {
    if (count($module_ids) == 0) {
		  return false;
		}
		// Only allow timing if ALL the modules of the paper allow
    $mod_id_list = implode(',', $module_ids);
		
    $stmt = $db->prepare("SELECT id FROM modules WHERE id IN ($mod_id_list) AND timed_exams = 0");
    $stmt->execute();
    $stmt->store_result();
    $allow_timing = ($stmt->num_rows === 0);
    $stmt->close();

    return $allow_timing;
  }

  public static function get_vle_api_data($vle_apis) {
    // Set up mapping APIs
    $configObject = Config::get_instance();
    
    if (is_array($vle_apis)) {
      foreach (array_keys($vle_apis) as $vle_api_id) {
        $classname = 'CM_' .$vle_api_id;
        require_once $configObject->get('cfg_web_root') . "/plugins/CM/{$classname}.class.php";
        $api = new $classname();
        $vle_apis[$vle_api_id]['name'] = $api->getFriendlyName(false, true);
        $vle_apis[$vle_api_id]['levels'] = $api->getMappingLevels();
      }
    }
    return $vle_apis;
  }
  
  public static function paper_types($idMod, $show_retired, $db) {
    $userObject = UserObject::get_instance();

    $paper_types = array();
    
    if ($idMod == '0') {    // Unused papers.
      if ($show_retired) {
        $sql = 'SELECT DISTINCT paper_type, COUNT(properties.property_id)
             FROM properties LEFT JOIN properties_modules
             ON properties.property_id = properties_modules.property_id
             WHERE idMod IS NULL
             AND paper_ownerID = ?
             AND deleted IS NULL
             GROUP BY paper_type
             ORDER BY paper_type';
      } else {
        $sql = 'SELECT DISTINCT paper_type, COUNT(properties.property_id)
             FROM properties LEFT JOIN properties_modules
             ON properties.property_id = properties_modules.property_id
             WHERE idMod IS NULL
             AND paper_ownerID = ?
             AND deleted IS NULL
             AND retired IS NULL
             GROUP BY paper_type
             ORDER BY paper_type';       
      }
      $result = $db->prepare($sql);
      $result->bind_param('i', $userObject->get_user_ID());
    } else {
      if ($show_retired) {
        $sql = 'SELECT DISTINCT paper_type, COUNT(properties.property_id)
             FROM properties, properties_modules
             WHERE properties.property_id = properties_modules.property_id
             AND idMod = ?
             AND deleted IS NULL
             GROUP BY paper_type
             ORDER BY paper_type';
      } else {
        $sql = 'SELECT DISTINCT paper_type, COUNT(properties.property_id)
             FROM properties, properties_modules
             WHERE properties.property_id = properties_modules.property_id
             AND idMod = ?
             AND deleted IS NULL
             AND retired IS NULL
             GROUP BY paper_type
             ORDER BY paper_type';      
      }
      $result = $db->prepare($sql);
      $result->bind_param('i', $idMod);
    }    
    
    $result->execute();
    $result->bind_result($type, $number);
    while ($result->fetch()) {
      $paper_types[$type] = $number;
    }
    $result->close();
    
    return $paper_types;
  }
}
?>

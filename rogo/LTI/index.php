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
 * LTI landing page.
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */

require_once '../include/staff_student_auth.inc';
require_once '../include/sidebar_menu.inc';

require_once '../config/index.inc';

require_once '../classes/searchutils.class.php';
require_once '../classes/dateutils.class.php';
require_once '../classes/userutils.class.php';
require_once '../classes/moduleutils.class.php';
require_once '../classes/personal_folders.php';
require_once '../classes/lti_integration.class.php';
require_once '../classes/smsutils.class.php';
require_once '../classes/schoolutils.class.php';
require_once '../classes/facultyutils.class.php';

function listtreemodules($mysqli, $moduleid, $block_id, $plk, $flat = false, $explode = false) {
  $icons = array('formative', 'progress', 'summative', 'survey', 'osce', 'offline', 'peer_review');

  $configObject = Config::get_instance();

  $moduleidorig = $moduleid;
  $moduleid = module_utils::get_idMod($moduleid, $mysqli);
  
  $sql = "SELECT DISTINCT crypt_name, paper_type, paper_title, retired, idMod FROM properties, properties_modules WHERE idMod = ? and properties.property_id = properties_modules.property_id AND deleted IS NULL AND paper_type IN ('0','1','3','4') ORDER BY paper_type, paper_title";
  $results2 = $mysqli->prepare($sql);
  $results2->bind_param('i', $moduleid);
  $results2->execute();
  $results2->bind_result($crypt_name, $paper_type, $paper_title, $retired, $moduleID);
  $results2->store_result();
  if ($results2->num_rows() > 0) {
    $rt = $results2->num_rows();
    echo '<div>';
    while ($results2->fetch()) {
      if (strtolower($_SESSION['_lti_context']['resource_link_title']) == strtolower($paper_title)) {
        $checked = ' checked';
      } else {
        $checked = '';
      }
      $extra = "<input type=\"radio\" name=\"paperlinkID\" id=\"paperlinkID-$plk\" value=\"$plk\"$checked><label for=\"paperlinkID-$plk\">";
      $extra1 = "</label>";
      
      echo "<div style=\"padding-left:20px\">$extra<img src=\"../artwork/" . $icons[$paper_type] . "_16.gif\" width=\"16\" height=\"16\" alt=\"" . $paper_type . "\" />&nbsp;" .  $paper_title . "$extra1</div>\n";

      $_SESSION['postlookup'][$plk] = array($crypt_name, $moduleid);
      $plk++;
    }
    echo '</div>';
    $block_id++;
  } else {
    // no papers
  }
  $results2->close();

  return (array($block_id, $plk));
}

$lti = UoN_LTI::get_instance();

if (!$lti->valid) {
  $tempvar = $lti->message;
  if (!isset($string[$tempvar])) {
    $string[$tempvar] = $lti->message;
  }
  $message = $string[$tempvar];
  UserNotices::display_notice($string['LTIFAILURE'], $message, '../artwork/access_denied.png', '#C00000');
  $mysqli->close();
  exit;
}

if (!isset($lti_i)) {
  $lti_i = lti_integration::load();
}

if (isset($_REQUEST['paperlinkID'])) {
  list($retlookup, $retlookup2) = $_SESSION['postlookup'][$_REQUEST['paperlinkID']];
  unset($_SESSION['postlookup']);
  if ($retlookup > 0) {
    $info = $lti->getResourceKey(1);
    $lti->add_lti_resource($retlookup, 'paper');
  }
}
unset($_SESSION['postlookup']);

$returned = $lti->lookup_lti_resource();

if (!$lti->isInstructor()) {
  //student
  if ($returned === false) {
    // no data selected for this
    UserNotices::display_notice($string['warning'], $string['ltinotconfigured'], '../artwork/access_denied.png', $title_color = '#C00000');
    echo "\n</body>\n</html>\n";
    exit();
  } else {
    //valid data
    list($c_internal_id, $upd) = $lti->lookup_lti_context();
    $session = date_utils::get_current_academic_year();

    if (is_null($c_internal_id)) {
   //   $lti_i::invalid_module_code($c_internal_id, $data, 'no returned data');
    }
    $data = $lti_i::module_code_translate($c_internal_id);

    foreach ($data as $v) {
      $returned_check = module_utils::get_full_details_by_name($v[1], $mysqli);
      if (!UserUtils::is_user_on_module_by_name($userObject->get_user_ID(), $v[1], $session, $mysqli) and $returned_check !== false and $lti_i::allow_module_self_reg($v)) {
        if ($returned_check['active'] == 1 and $returned_check['selfenroll'] == 1 and !UserUtils::is_user_on_module_by_name($userObject->get_user_ID(), $v[1], $session, $mysqli)) {
          // Insert new module enrollment
          UserUtils::add_student_to_module_by_name($userObject->get_user_ID(), $v[1], 1, $session, $mysqli);
        }
      }
    }

    $_SESSION['lti']['paperlink'] = $returned[0];
    header("location: ../paper/user_index.php?id=" . $returned[0]);
    echo "Please click <a href='../paper/user_index.php?id=" . $returned[0] . ".>here</a> to continue";
    exit();

  }
} else {
  //staff

  if ($returned !== false) {
    // goto link

    $returned2 = $lti->lookup_lti_context();
    $mod = $returned2[0];
    $data = $lti_i::module_code_translate($mod);
    foreach ($data as $v) {
      if (!$userObject->is_staff_user_on_module($v[1]) and $lti_i::allow_staff_module_register($v) and $userObject->has_role(array('Staff', 'Admin', 'SysAdmin'))) {
        UserUtils::add_staff_to_module_by_modulecode($userObject->get_user_ID(), $v[1], $mysqli);
      } elseif (!$userObject->is_staff_user_on_module($v[1]) and !$lti_i::allow_staff_module_register($v) and $userObject->has_role(array('Staff', 'Admin', 'SysAdmin'))) {
        UserNotices::display_notice($string['NotAddedToModuleTitle'], $string['NotAddedToModule'] . $v[1], '../artwork/exclamation_64.png','#C00000');
        echo "\n</body>\n</html>\n";
        exit();
      }
    }

    if (!$lti_i::allow_staff_edit_link()) {
      $_SESSION['lti']['paperlink'] = $returned[0];
      header("location: ../paper/user_index.php?id=" . $returned[0]);
      echo "Please click <a href='../paper/user_index.php?id=" . $returned[0] . ".>here</a> to continue";
      exit();
    } else {
      // allow editing of the stored link
      //TODO NO SUPPORT YET IMPLIMENTED
    }

  } else {
    // no existing stored link so need to create one
    if (!$userObject->has_role(array('Staff', 'Admin', 'SysAdmin'))) {
      UserNotices::display_notice($string['NoModCreateTitle2'], $string['NoModCreate2'], '../artwork/exclamation_64.png','#C00000');
      echo "\n</body>\n</html>\n";
      exit();
    }
    $returned2 = $lti->lookup_lti_context();

    if ($returned2 === false) {
      //no context
      $data = $lti_i::module_code_translate($lti->getCourseName(), $lti->get_context_title());

      $problem = false;
      foreach ($data as $v) {
        if (!module_utils::module_exists($v[1], $mysqli) and $lti_i::allow_module_create($v) ) {
          if (!$userObject->has_role(array('Staff', 'Admin', 'SysAdmin'))) {
            UserNotices::display_notice($string['NoModCreateTitle2'], $string['NoModCreate2'] . $v[1], '../artwork/exclamation_64.png','#C00000');
            echo "\n</body>\n</html>\n";
            exit();
          }
          $peer = 1;
          $external = 1;
          $stdset = 0;
          $mapping = 1;
          $neg_marking = 1;

          $selfEnroll = 0;
          if ($v[0] == 'Manual') {
            $selfEnroll = 1;
            $peer = 0;
            $external = 0;
            $stdset = 0;
            $mapping = 0;
            $neg_marking = 1;
          }
          $sms_api = $lti_i::sms_api($v);
          $schoolID = SchoolUtils::get_school_id_by_name($v[3], $mysqli);
          $modcreate = module_utils::add_modules($v[1], $v[5], 1, $schoolID, '', $sms_api, $selfEnroll, $peer, $external, $stdset, $mapping, $neg_marking, 0, $mysqli, 1, 0, 1, 1, '07/01');
          if ($modcreate === false) {
            $problem = true;
          }
        } elseif (!module_utils::module_exists($v[1], $mysqli) and  !$lti_i::allow_module_create($v)) {
          UserNotices::display_notice($string['NoModCreateTitle'], $string['NoModCreate'] . $v[1], '../artwork/exclamation_64.png','#C00000');
          echo "\n</body>\n</html>\n";
          exit();
        }
        if (!$userObject->is_staff_user_on_module($v[1]) and $lti_i::allow_staff_module_register($v) and $userObject->has_role(array('Staff', 'Admin', 'SysAdmin')) and module_utils::is_allowed_add_team_members_by_name($v[1],$mysqli) ) {
          UserUtils::add_staff_to_module_by_modulecode($userObject->get_user_ID(), $v[1], $mysqli);
        } elseif (!$userObject->is_staff_user_on_module($v[1]) and !$lti_i::allow_staff_module_register($v)) {
          UserNotices::display_notice($string['NotAddedToModuleTitle'], $string['NotAddedToModule'] . $v[1], '../artwork/exclamation_64.png','#C00000');
          echo "\n</body>\n</html>\n";
          exit();
        }
      }
      $module_store = $lti_i::module_code_translated_store($data);
      if($problem === false ) {
        $lti->add_lti_context($module_store);
      }
      $returned2 = $lti->lookup_lti_context();
    }
    $mod = $returned2[0];
    $data = $lti_i::module_code_translate($mod);
    foreach ($data as $v) {
      if (!$userObject->is_staff_user_on_module($v[1]) and $lti_i::allow_staff_module_register($v) and $userObject->has_role(array('Staff', 'Admin', 'SysAdmin')) and module_utils::is_allowed_add_team_members_by_name($v[1],$mysqli) ) {
        UserUtils::add_staff_to_module_by_modulecode($userObject->get_user_ID(), $v[1], $mysqli);
      } elseif (!$userObject->is_staff_user_on_module($v[1]) and !$lti_i::allow_staff_module_register($v)) {
        UserNotices::display_notice($string['NotAddedToModuleTitle'], $string['NotAddedToModule'] . $v[1], '../artwork/exclamation_64.png','#C00000');
        echo "\n</body>\n</html>\n";
        exit();
      }
    }
    list($c_internal_id, $upd) = $returned2;
    $moduleid = $c_internal_id;
    echo <<<END
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset={$configObject->get('cfg_page_charset')}" />

  <title>Rog&#333; {$configObject->get('cfg_install_type')}</title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <style type="text/css">
  body {padding-left:20px; background-color:transparent !important; line-height:140%}
  h1 {font-size:160%; color:#295AAD}
  .info_bar {margin-bottom:8px}
  </style>
   {$configObject->get('cfg_js_root')}
</head>
<body>
<div id="content" class="content">

END;

    $plk = 0;
    $block_id = 0;

    if (isset($error)) {
      foreach ($error as $e) {
        echo $e;
      }
    }

    @ob_flush();
    @ob_start();
    
    // If there is a context and therefore a course already selected display that.
    $modinfo = '';
    $exit = 0;

    foreach ($data as $v) {
      $modinfo = $modinfo . ', ' . $v[1];
      if ($v[1] == '') {
        $exit = 1;
      }
    }
    $modinfo = substr($modinfo, 2);

    echo '<h1>' . sprintf($string['module'], $modinfo) . '</h1>';
    $msg = 'First time configuration. Please select the paper you wish to use in this external tool link.';
    echo $notice->info_strip($msg, 100);
    
    echo '<form method="post">';

    foreach ($data as $v) {
      $moduleid = $v[1];

      list($block_id, $plk) = listtreemodules($mysqli, $moduleid, $block_id, $plk, true);
    }
    echo "<br /><div><input type=\"submit\" name=\"submit\" value=\"" . $string['ok'] . "\" class=\"ok\" style=\"margin-left:20px\" /></form></div></form>\n";
    echo '<br />';
    if ($exit == 1) {
      $plk = 0;
      $modinfo = "Undefined Module. Please contact Support.";
    }

    if ($plk == 0) {
      @ob_clean();
      unset($_SESSION['_lti_context']);
      unset($_SESSION['lti']);
      UserNotices::display_notice($string['NoPapers'], $string['NoPapersDesc'], '../artwork/access_denied.png', '#C00000');

      echo '<p>Module(s): ' . $modinfo . '</p>';
    }
  }
}
?>

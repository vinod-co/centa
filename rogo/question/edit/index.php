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
* @author Rob Ingram
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require_once '../../include/staff_auth.inc';
require_once '../../classes/questionEdit.class.php';
require_once '../../classes/logger.class.php';
require_once '../../classes/viewhelper.class.php';
require_once '../../classes/stateutils.class.php';
require_once '../../classes/moduleutils.class.php';
require_once '../../classes/questioninfo.class.php';
require_once '../../classes/question_status.class.php';
require_once '../../classes/paperutils.class.php';
require_once '../../include/edit.inc';
require_once '../../include/media.inc';
require_once '../../include/metadata.inc';
require_once '../../include/mapping.inc';

$state = $stateutil->getState();

$question = null;
$logger = new Logger($mysqli);
$paper_id = (!isset($_GET['paperID'])) ? -1 : $_GET['paperID'];
$module = (!isset($_GET['module'])) ? '' : $_GET['module'];
$folder = (!isset($_REQUEST['folder'])) ? '' : $_REQUEST['folder'];
$scrofy = (!isset($_REQUEST['scrOfY'])) ? '' : $_REQUEST['scrOfY'];
$calling = (!isset($_REQUEST['calling'])) ? '' : $_REQUEST['calling'];
$ListKeyword = (!isset($_REQUEST['keyword'])) ? '' : $_REQUEST['keyword'];
$team = (!isset($_REQUEST['team'])) ? '' : $_REQUEST['team'];

function save_options($question, $userObject, $db) {
  $unified_part_names = $question->get_unified_fields();

  for ($option_no = 1; $option_no <= $question->max_options; $option_no++) {
    $option = null;

    if (isset($_POST["optionid$option_no"]) and $_POST["optionid$option_no"] != -1) {
      // Editing existing option
      $option = $question->options[$_POST["optionid$option_no"]];
      $part_names = $option->get_editable_fields();

      // Build arrays for compound fields
      $compound_fields = $option->get_compound_fields();
      if (!isset($existing_values)) $existing_values = array();
      $option->populate_compound(array_keys($compound_fields), $_POST, $existing_values, 'option_');

      // Save editable fields that aren't unified
      $option->populate($part_names, $option_no, $_POST, array_merge(array_keys($unified_part_names), array_keys($compound_fields)), 'option_');

      // Save fields that are the same across options
      $option->populate_unified($unified_part_names, $_POST, array_keys($compound_fields), 'option_');
    } else {
      // Create new option if have required data
      $option = OptionEdit::option_factory($db, $userObject->get_user_ID(), $question, $option_no, $string, array('marks' => 1));

      if ($option->minimum_fields_exist($_POST, $_FILES, $option_no)) {
        $correct_fb = (isset($_POST["option_correct_fback$option_no"])) ? $_POST["option_correct_fback$option_no"] : '';
        $incorrect_fb = (isset($_POST["option_incorrect_fback$option_no"])) ? $_POST["option_incorrect_fback$option_no"] : '';

        $part_names = $option->get_editable_fields();

        // Build arrays for compound fields
        $compound_fields = $option->get_compound_fields();
        if (!isset($existing_values)) $existing_values = array();
        $option->populate_compound(array_keys($compound_fields), $_POST, $existing_values, 'option_');

        // Save editable fields that aren't unified
        $option->populate($part_names, $option_no, $_POST, array_merge(array_keys($unified_part_names), array_keys($compound_fields)), 'option_');

        // Save fields that are the same across options
        $option->populate_unified($unified_part_names, $_POST, array_keys($compound_fields), 'option_', false);

        $question->options[] = $option;
      }
    }

    if ($option != null and !in_array('media', $question->get_compound_fields())) {
      // Handle changes in media
      $old_media = $option->get_media();
      if (isset($_FILES["option_media$option_no"]) and $_FILES["option_media$option_no"]['name'] != $old_media['filename'] and ($_FILES["option_media$option_no"]['name'] != 'none' and $_FILES["option_media$option_no"]['name'] != '')) {
        if ($old_media['filename'] != '') {
          deleteMedia($old_media['filename']);
        }
        $option->set_media(uploadFile("option_media$option_no"));
      } else {
        // Delete existing media if asked
        if (isset($_POST["delete_media$option_no"]) AND $_POST["delete_media$option_no"] == 'on') {
          deleteMedia($old_media['filename']);
          $option->set_media(array('filename' => '', 'width' => 0, 'height' => 0));
        }
      }
    }
  }
}

$paper_count = 0;
$critical_error = '';
$q_no = '';
$q_type_full = '';
$errors = array();

if (!isset($_REQUEST['q_id']) or $_REQUEST['q_id'] == -1) {
  // We're adding a new question
  $mode = $string['add'];

  if (!isset($_GET['type'])) {
    $critical_error = $string['typeundefined'];
  } elseif (!in_array($_GET['type'], QuestionEdit::$types)) {
    $critical_error = sprintf($string['typeinvalid'], htmlentities($_GET['type']));
  } else {
    try {
      $question = QuestionEdit::question_factory($mysqli, $userObject, $string, $_GET['type']);
      $question->set_type($_GET['type']);
      $question->set_owner_id($userObject->get_user_ID());
      $question->set_teams(Paper_utils::get_modules($paper_id, $mysqli));
    } catch (ClassNotFoundException $ex) {
      $critical_error = $ex->getMessage();
    }
  }
} else {
  // We're editing an existing question
  $mode = $string['edit'];

  try {
    $question = QuestionEdit::question_factory($mysqli, $userObject, $string, $_REQUEST['q_id']);
  } catch (Exception $ex) {
    $critical_error = $ex->getMessage();
  }
}

// Handle upload of files for question types that require it
if ($critical_error == '' and $question->requires_media() and (isset($_POST['submit_media']) or isset($_POST['q_media']))) {
  if (isset($_POST['q_media']) and $_POST['q_media'] != '') {
    $new_media['filename'] = $_POST['q_media'];
    $new_media['width'] = (isset($_POST['q_media_width']) and $_POST['q_media_width'] != '') ? $_POST['q_media_width'] : 0;
    $new_media['height'] = (isset($_POST['q_media_height']) and $_POST['q_media_height'] != '') ? $_POST['q_media_height'] : 0;
  } else {
    $new_media = uploadFile('q_media');
  }
  if ($new_media !== false) {
    $question->set_media($new_media);
  } else {
    $critical_error = $string['mediauploaderror'];
  }

  // Handle label images for Labelling questions. These never really hit the question object as items in their own right
  // but are used in parameters to the Flash setup JS function
  if ($question->get_type() == 'labelling') {
    $label_images = array();
    for ($i = 1; $i <= 6; $i++) {
      if (isset($_FILES['label_media' . $i]) and $_FILES['label_media' . $i]['name'] != '') {
        $lab_media = uploadFile('label_media' . $i);
        if ($lab_media !== false) {
          $label_images[] = $lab_media;
        }
      }
    }
  }
}

if ($critical_error == '') {
  $question->add_default_correction_behaviours($cfg_web_root);

  if ($mode == 'Edit') {
    if (isset($_GET['qNo'])) {
      $q_no = $_GET['qNo'];
    } else {
      $q_no = $question->get_question_number($paper_id);
    }
    // If existing question, check how many summative papers it is on
    $paper_count = $question->get_other_summative_count($paper_id);
  }

  // Get any existing media
  $current_media = $question->get_media();
	
  $do_save = false;
  $show_media_upload = false;
  $show_correction_intermediate = false;
  if ($question->requires_media() and $current_media['filename'] == '') {
    $show_media_upload = true;

  } elseif (isset($_POST['submit']) and $_POST['submit'] == $string['limitedsave']) {

    if ($question->requires_correction_intermediate() and (!isset($_POST['corrected']) or $_POST['corrected'] != 'OK')) {
      $show_correction_intermediate = true;
		} else {
      $unified_part_names = $question->get_unified_fields();
      $save_individual = in_array('correct', array_keys($unified_part_names));
			
      if ($save_individual) {
        // Calculation, MCQ
        $part_names = $question->get_change_fields();
        $fields = array();
        foreach ($part_names as $field) {
          if (isset($_POST[$field])) $fields[$field] = $_POST[$field];
        }
        $errors = $question->update_correct($fields, $paper_id);
        
        foreach($fields as $feild_to_update => $value) {
					if (stristr($feild_to_update, 'option_') !== false) {
						continue;
					}
					$call = 'set_' . $feild_to_update;
					$question->$call($value);
        }
        
      } else {
        // Dichotomous, MRQ, Ranking, extmatch, matrix, textbox
        $first = reset($question->options);
        $compound_part_names = $first->get_compound_fields();

        if (is_array($compound_part_names) and in_array('correct', array_keys($compound_part_names))) {
          $loop_limit = $question->max_stems;
        } elseif ($question->allow_new_options()) {
          $loop_limit = $question->max_options;
        } else {
          $loop_limit = count($question->options);
        }
        $part_names = $question->get_change_fields();

        $correct_answers = array();
        foreach ($part_names as $field) {
          for ($i = 1; $i <= $loop_limit; $i++) {
            if (isset($_POST[$field . $i])) {
              $correct_answers[$field][] = $_POST[$field . $i];
            } elseif (isset($_POST[$field])) {
              $correct_answers[$field] = $_POST[$field];
              break;
            } else {
              $correct_answers[$field][] = $question->get_answer_negative();
            }
          }
        }

        $errors = $question->update_correct($correct_answers, $paper_id);
      }

      $question_teams = array();

      if (isset($_POST['teams'])) {
        foreach ($_POST['teams'] as $idMod) {
          $question_teams[$idMod] = module_utils::get_moduleid_from_id($idMod, $mysqli);
        }
      }
      $question->set_teams($question_teams);


      // Save metadata
      $part_names = array('bloom', 'status', 'correct_fback', 'incorrect_fback');
      if (!isset($_POST['teams'])) {
        $_POST['teams'] = array();
      }
      foreach ($part_names as $section_name) {
        if (isset($_POST["$section_name"])) {
          $method = "set_$section_name";
          $question->$method($_POST["$section_name"]);
        }
      }

      if ($question->allow_option_edit()) {
        save_options($question, $userObject, $mysqli);
      }

      $do_save = true;
    }
  } elseif ((isset($_POST['submit']) and $_POST['submit'] == $string['save']) or isset($_POST['addbank']) or isset($_POST['addpaper'])) {
    // Save data
    if ($question->id == -1 or check_fullSave($question->id, $mysqli)) {

      $part_names = $question->get_editable_fields();
      $compound_fields = $question->get_compound_fields();
      $question->populate($part_names, $_POST, $compound_fields);

      // Handle changes in media if not a compound field
      if (!in_array('media', $question->get_compound_fields())) {
        $question->populate_media('q_media', $_FILES, $_POST);
      }

      // Save compound fields
      $question->populate_compound($compound_fields, $_POST, array('media'), $prefix='question_');

      // Handle changes in media for compound fields
      if (in_array('media', $compound_fields)) {
        $question->populate_compound_media($_FILES, $_POST, 'q_media', 'question_media');
      }

      // Strip MS Office HTML.
      $question->set_scenario(clearMSOtags($question->get_scenario()));
      $question->set_leadin(clearMSOtags($question->get_leadin()));

      $question_teams = array();
      if (isset($_POST['teams'])) {
        //$question_teams = array_combine($_POST['teams'], $_POST['teams']);
        foreach ($_POST['teams'] as $idMod) {
          $question_teams[$idMod] = module_utils::get_moduleid_from_id($idMod, $mysqli);
        }
      }
      $question->set_teams($question_teams);

      save_options($question, $userObject, $mysqli);

      $do_save = true;
    }
  } elseif (isset($_POST['submit-cancel']) and $_POST['submit-cancel'] == $string['cancel']) {
    redirect($userObject, $question->id, $configObject, $mysqli);
  }

  if ($do_save) {
    // If not errored then save the question
    if (count($errors) == 0) {
      try {
        if (!$question->save()) {
          $errors[] = $string['datasaveerror'];
        } else {
          // Possibility that we might be converting a MRQ to MCQ
          if (isset($_POST['mcqconvert']) and $_POST['mcqconvert'] == '1') {
            $i = 1;
            $correct_option = 0;
            foreach ($question->options as $option) {
              if ($option->get_correct() == 'y') {
                $correct_option = $i;
                break;
              }
              $i++;
            }
            $question = $question->convert_to_mcq($correct_option);
          }

          // Insert into Papers
          if (isset($_POST['addpaper'])) {
            insert_into_papers($paper_id, $question->id, $mysqli);
            $logger->track_change('Paper', $paper_id, $userObject->get_user_ID(), '', $question->id, 'Add Question');
          }

          save_keywords($question, $userObject->get_user_ID(), true, $mysqli, $string);

          if (isset($_POST['objective_modules'])) {
            // Write out curriculum mapping.
            save_objective_mappings($mysqli, $_POST['objective_modules'], $paper_id, $question->id);
          }

          // Stuff not to do on correction/limited save
          if (!isset($_POST['submit']) or $_POST['submit'] != $string['correct']) {
            // Save review comments and responses
            if (isset($_POST['comment_ids']) and isset($_POST['actions']) and isset($_POST['responses'])) {
              save_external_responses($mysqli, $question, $_POST['comment_ids'], $_POST['actions'], $_POST['responses'], $paper_id);
            }

            // For likert, save the scale to a state to ease creation of multiple questions with same scale
            if ($mode == 'Add' and $question->get_type() == 'likert') {
              $scale_type = $question->get_scale_type();
              $stateutil->setState($userObject->get_user_ID(), 'likert_format', $scale_type, '/question/edit/index.php', $mysqli);

              if ($scale_type == 'custom') {
                $stateutil->setState($userObject->get_user_ID(), 'likert_format', implode('|', $question->get_all_custom_scales()), '/question/edit/index.php', $mysqli);
              }
            }
          }
        }
      } catch (ValidationException $vex) {
        $errors[] = $vex->getMessage();
      }
    }

    if (count($errors) == 0) redirect($userObject, $question->id, $configObject, $mysqli);
  }

  $q_type_display = '';
  $q_type_full = '';

  if ($question->get_type() != 'info' and !empty($q_no)) {
    $q_type_display .= ' ' . $q_no;
  }
  if ($question->get_type() != '') {
    $q_type_full .= $string[$question->get_type()];
    $q_type_display .= "&nbsp;</strong>$q_type_full";
  }

  // Set come classes and attributes that we're going to use to disable fields that aren't editable when locked
  $dis_class = $dis_readonly = '';
  disable_locked($question, $dis_class, $dis_readonly);
} else {
  // Bad things have happened
  $q_type_display = '';

  $msg = sprintf($string['furtherassistance'], $configObject->get('support_email'), $configObject->get('support_email'));
  $notice->display_notice_and_exit($mysqli, $string['error'], $critical_error, $string['error'], '/artwork/page_not_found.png', '#C00000', true, true);
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $configObject->get('cfg_page_charset') ?>" />

<title><?php echo $mode . ' ' . $string['question'] . ' - ' . $q_type_full .  ' ' . $configObject->get('cfg_install_type') ?></title>

<link rel="stylesheet" href="../../css/body.css" type="text/css" />
<link rel="stylesheet" href="../../css/header.css" type="text/css" />
<link rel="stylesheet" href="../../css/screen.css" type="text/css" />
<link rel="stylesheet" href="../../css/add_edit.css" type="text/css" />
<link rel="stylesheet" href="../../css/mapping_form.css" type="text/css" />
<link rel="stylesheet" href="../../css/warnings.css" type="text/css" />

<?php
// Override this variable with a specific configuration file for the question editor.
$cfg_editor_javascript = <<< SCRIPT
{$configObject->get('cfg_js_root')}
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_config_question_editor.js"></script>
SCRIPT;

echo $cfg_editor_javascript;
?>
<script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.4.min.js"></script>
<script type="text/javascript" src="../../js/system_tooltips.js"></script>
<?php
// Override this variable with a specific configuration file for the question editor.
$cfg_editor_javascript = <<< SCRIPT
{$configObject->get('cfg_js_root')}
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="{$configObject->get('cfg_root_path')}/tools/tinymce/jscripts/tiny_mce/tiny_config_question_editor.js"></script>
SCRIPT;

echo $cfg_editor_javascript;
?><script type="text/javascript" src="../../js/state.js"></script>
<script type="text/javascript" src="../../js/staff_help.js"></script>
<script type="text/javascript" src="../../js/jquery.addedit.js"></script>
<script type="text/javascript" src="../../js/jquery.mappingform.js"></script>
<script type="text/javascript" src="../../js/jquery.formhelpers.js"></script>
<?php
if ($question != null and file_exists($cfg_web_root . 'js/validation/jquery.' . $question->get_type() . '.js')):
?>
<script type="text/javascript" src="../../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../../js/validation/jquery.<?php echo $question->get_type() ?>.js"></script>
<script type="text/javascript" src="../../js/toprightmenu.js"></script>
<?php
endif;
if ($question != null and $question->requires_flash()):
?>
<script type="text/javascript" src="../../js/ie_fix.js"></script>
<script type="text/javascript" src="../../js/flash_include.js"></script>
<!--HTML5 part start ---------- -->
<script type='text/javascript'><?php echo "var lang_string = ".  json_encode($jstring) . ";\n";?></script>
<script type="text/javascript" src="../../js/html5.images.js"></script>
<script type="text/javascript" src="../../js/qsharedf.js"></script>
<script type="text/javascript" src="../../js/qlabelling.js"></script>
<script type="text/javascript" src="../../js/qhotspot.js"></script>
<script type="text/javascript" src="../../js/qarea.js"></script>
<!--HTML5 part end-->
<?php
endif;
?>
<script>
var qType = '<?php if (isset($question)) echo $question->get_type() ?>';
var lang = {
<?php
$langstrings = array('allowpartial', 'validationerror', 'enterleadin', 'enterdescription', 'showmore', 'hidemore', 'enteroption', 'enterformula', 'enteroptionshort', 'enteroption_kw', 'mrqconvert', 'entervignette', 'enteroptiontext', 'selectarea', 'randomenterquestion', 'mappingwarning', 'markchangewarning');
$first = true;
foreach ($langstrings as $langstring) {
  if (!$first) {
    echo ',';
  }
  echo "'{$langstring}':'{$string[$langstring]}'";
  $first = false;
}
?>
};
<?php
if (!empty($_GET['tab']) and in_array($_GET['tab'], array('changes', 'comments', 'performance', 'mapping'))):
?>
$(function () {
  $('.tabs li a[rel=<?php echo $_GET['tab'] ?>]').trigger('click');
});
<?php
endif;
?>
</script>
<script type="text/javascript" src="../../tools/mee/mee/js/mee_src.js"></script>
</head>
<body>
<?php
  require '../../include/toprightmenu.inc';
	
	echo draw_toprightmenu();
?>
  <div id="debug" class="debug"></div>
	<div id="page-header">
		<div><img src="../../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
		<div id="page-header-inner">
			<h1><strong><?php
      if ($mode == 'Add') echo 'Add ';
      echo $string['question'] . $q_type_display;
      ?></h1>
		</div>

<?php
if ($critical_error == '') {
  $mapping_enabled = ($question->allow_mapping()) ? '' : ' class="disabled"';
  $creation_date = ($mode == 'Edit') ? strftime( $configObject->get('cfg_short_date'), $question->get_created('timestamp')) : strftime( $configObject->get('cfg_short_date'), time());
  $modified_date = ($question->get_last_edited('timestamp')) ? strftime( $configObject->get('cfg_short_date'), $question->get_last_edited('timestamp')) : $string['na'];
?>
    <div class="tab-bar">
      <div class="tab-holder">
        <p class="question-stats">
          <?php echo $string['created'] ?>&nbsp;<?php echo $creation_date ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $string['modified'] ?>&nbsp;<?php echo $modified_date ?>
        </p>
        <ol class="tabs">
          <li class="on"><a href="#" rel="editor"><?php echo $string['editor'] ?></a></li>
          <li><a href="#" rel="changes"><?php echo $string['changes'] ?></a></li>
          <li><a href="#" rel="comments"><?php echo $string['comments'] ?></a></li>
          <li><a href="#" rel="performance"><?php echo $string['performance'] ?></a></li>
          <li<?php echo $mapping_enabled ?>><a href="#" rel="mapping"><?php echo $string['mapping'] ?></a></li>
        </ol>
      </div>
    </div>
<?php
  $banner_spacer = '';
  $editor = $question->get_checkout_author_name();
  $q_disabled = check_edit_rights($question->id, $question->get_checkout_author_id(), $editor, $question->get_checkout_time('timestamp'), $question->get_locked(), $mysqli, $userObject);

  if ($q_disabled != '') {
    $banner_spacer = ' class="banner-spaced"';

    if ($q_disabled == 'locked') {
?>
    <div class="yellowwarn" style="vertical-align:middle; font-size:90%"><img src="../../artwork/paper_locked_padlock.png" width="32" height="32" alt="Locked" style="float:left" /><div style="float:left">&nbsp;&nbsp;<?php echo $string['lockedmsg'] ?></div></div>
<?php
    } elseif ($q_disabled == ' disabled') {
?>
    <div class="yellowwarn" style="vertical-align:middle; font-size:90%"><img src="../../artwork/paper_locked_padlock.png" width="32" height="32" alt="Locked" style="float:left" /><div style="float:left">&nbsp;&nbsp;<?php echo $string['questionlocked'] . " $editor. " . $string['isinreadonly'] ?></div></div>
<?php
    }
  }
}
?>
	</div>

<?php
if ($critical_error != '') {
  // We have a major error so won't even display a form
?>
  <div id="major-error" class="edit-spacer">
    <div id="major-error-inner">
      <h1><?php echo $string['error'] ?></h1>
      <p><?php echo $critical_error ?></p>
    </div>
  </div>
<?php
} else {

  $query_string = '';
  if ($question->id != -1) {
    $query_string = '?q_id=' . $question->id;
  } else {
    $query_string .= '?type=' . $question->get_type();;
  }
  $query_string .= ($paper_id != -1) ? '&amp;paperID=' . $paper_id : '';
  $query_string .= ($module != '') ? '&amp;module=' . $module : '';

?>
	<form id="edit_form" name="edit_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] . $query_string ?>" enctype="multipart/form-data" class="clearinput">
<?php
  if ($show_media_upload) {
    $upload_file = "../../include/question/addedit/media_upload/media_upload_{$question->get_type()}.php";
    include $upload_file;
  }
?>

    <div id="tabbed-content"<?php echo $banner_spacer ?>>
			<div id="editor" class="tab-area">

				<div class="message">
					<p>
						<span class="mandatory">*</span> <?php echo $string['mandatory'] ?>
					</p>
				</div>

<?php
  if (count($errors) > 0) {
?>
        <div id="errors" class="form">
          <ul>
<?php
  foreach ($errors as $error) {
?>
            <li><?php echo $error ?></li>
<?php
  }
?>
          </ul>
        </div>

<?php
  }
?>
        <div id="question-holder" class="clearfix">
          <div class="form">
            <h2 class="midblue_header"><?php echo $string['question'] ?></h2>
          </div>

<?php
if ($question->get_type() != '') require_once '../../include/question/addedit/' . $question->get_type() . '.php'
?>

          <div class="form">
            <h2 class="midblue_header"><?php echo $string['metadata'] ?></h2>
          </div>

<?php
$q_teams = array();
if (count($question->get_teams()) > 0) {
  $q_teams = $question->get_teams();
} elseif (isset($module)) {
  $q_teams[$module] = module_utils::get_moduleid_from_id($module, $mysqli);
}

echo render_metadata($mysqli, $question, $question->use_bloom(), $q_teams, $q_disabled, $string, $userObject);
?>
        </div>
      </div>

      <div id="changes" class="tab-area">
<?php
$changes = $question->get_changes();
echo render_changes($changes, $string);
?>
      </div>

      <div id="comments" class="tab-area">
<?php
$comments = $question->get_comments($paper_id);
echo render_comments($comments, $string);
?>
      </div>

      <div id="performance" class="tab-area">
      <table style="font-size:90%; width:100%" class="data">
<?php
    echo "<tr><th></th><th>" . $string['papername'] . "</th><th>" . $string['screenno'] . "</th><th>" . $string['examdate'] . "</th><th>" . $string['cohort'] . "</th><th></th><th>" . $string['p'] . "</th><th>" . $string['d'] . "</th></tr>\n";
    $performance_array = ($question->id > -1) ? question_info::question_performance($question->id, $mysqli) : array();

    foreach ($performance_array as $paper => $performance) {
      echo "<tr><td><img src=\"../../artwork/" . $performance['icon'] . "\" width=\"16\" height=\"16\" /></td>";
      echo "<td>" . $performance['title'] . "</td>";
      echo "<td class=\"num\">" . $performance['screen'] . "</td>";
      $q_type = $question->get_type();
      if (isset($performance['performance'][1]['taken'])) {
        echo "<td>" . $performance['performance'][1]['taken'] . "</td><td class=\"num\">" . $performance['performance'][1]['cohort'] . "</td><td style=\"text-align:right\">" . question_info::display_parts($performance['performance'], $q_type) . "</td><td class=\"num\">" . question_info::display_p($performance['performance'], $q_type) . "</td><td class=\"num\">" . question_info::display_d($performance['performance'], $q_type) . "</td>";
      } else {
        echo "<td></td><td></td><td></td><td></td><td></td>";
      }
      echo "</tr>\n";
    }
?>
      </table>
      </div>

      <div id="mapping" class="tab-area" style="padding-left:10px; padding-right:10px">
<?php
echo render_objectives_mapping_form($mysqli, $paper_id, $string);
?>

      </div>
    </div>

    <div id="button-bar">
<?php
echo save_buttons($mode, $q_disabled, $question->get_locked(), $question->allow_correction(), $userObject->get_user_ID(), $question->get_checkout_author_id(), $paper_id, $paper_count, $string);
?>
      <input type="hidden" name="q_id" value="<?php echo $question->id ?>" />
      <input name="checkout_author" value="<?php echo $userObject->get_user_ID() ?>" type="hidden" />
      <input id="calling" name="calling" value="<?php echo $calling ?>" type="hidden" />
      <input id="module" name="module" value="<?php echo $module ?>" type="hidden" />
      <input id="folder" name="folder" value="<?php echo $folder ?>" type="hidden" />
      <input id="scrOfY" name="scrOfY" value="<?php echo $scrofy ?>" type="hidden" />
      <input id="paperID" name="paperID" value="<?php echo $paper_id ?>" type="hidden" />
      <input id="keyword" name="keyword" value="<?php echo $ListKeyword ?>" type="hidden" />
      <input id="team" name="team" value="<?php echo $team ?>" type="hidden" />
      <input id="question_id" name="question_id" value="<?php echo $question->id ?>" type="hidden" />
    </div>
  </form>
<?php
}
?>
</body>
</html>
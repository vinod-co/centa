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
 * @author Adam Clarke
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package
 */

require_once '../classes/logger.class.php';

function xml2array($xmlObject, $out = array()) {
  foreach ((array)$xmlObject as $index => $node) $out[$index] = (is_object($node)) ? xml2array($node) : $node;

  return $out;
}

class IE_Local_Save extends IE_Main {
  var $q_row = array();
  var $o_rows = array();
  var $o_row = array();
  var $db;
  var $statuses = array();

  // Rogo save parameters:
  // for saving questions
  // ownerID
  // q_group
  // keywords
  // bloom

  // main save function
  function Save($params, &$data) {
    global $mysqli, $string;

    echo "<h4>{$string['params']}</h4>";
    print_p($params);
    echo "<h4>{$string['othherdebug']}</h4>";

    $this->db = new Database();

    if (count($data->questions) == 0) {
      $this->AddError($string['noquestions']);

      return;
    }

    $paperid = $params->paper;

    $userObj = UserObject::get_instance();
    $userID = $userObj->get_user_ID();
    $db = new Database();
    $db->SetTable('properties');
    $db->AddField('*');
    $db->AddWhere('property_id', $paperid, 'i');
    $paper_row = $db->GetSingleRow();

    $ownerid = $userID;

    $data->ownerID = $userID;

    $nextscreen = 1;
    $nextid = 1;

    if ($paperid) {
      echo "{$string['addingtopaper']} $paperid<br>";

      $this->db->SetTable('papers');
      $this->db->AddField("max(screen) as screen");
      $this->db->AddField("max(display_pos) as display_pos");
      $this->db->AddWhere('paper', $paperid, 'i');
      $curpos = $this->db->GetSingleRow();

      $nextscreen = $curpos['screen'] + 1;
      $nextid = $curpos['display_pos'] + 1;
    }

    /*    // Get the actual ID of the module
        $this->db->SetTable('modules');
        $this->db->AddField('id');
        // Temp fix - if more than one team just get the first. Avoids error but doesn't fix the problem completely
        if (strpos($q_group, ',') !== false) {
          $q_group = strstr($q_group, ',', true);
        }
        $this->db->AddWhere('moduleid', $q_group, 's');
        $module_row = $this->db->GetSingleRow();
    */
    $module_id = -1;

    $paperutils = Paper_utils::get_instance();
    $module_id1 = $paperutils->get_modules($paper_row['property_id'], $mysqli);

    if ($module_id1 !== false) {
      $module_id = $module_id1;
    }

    $modutils = module_utils::get_instance();
    $q_group = $modutils->get_moduleid_from_id($module_id, $mysqli);

    if ($module_id !== false) {

      // Get a list of the team and user's keywords
      $user_keywords = array();
      if (is_array($module_id)) {
        foreach (array_keys($module_id) as $mod_id) {
          $user_keywordsl = $this->GetExistingKeywords($mod_id);
          $user_keywords = array_merge($user_keywords, $user_keywordsl);
        }
      } else {
        $user_keywords = $this->GetExistingKeywords($module_id);
      }
    }

    foreach ($data->questions as & $question) {
      $this->q_row = $this->db->GetBlankTableRow("questions");
      $this->o_row = $this->db->GetBlankTableRow("options");
      $this->o_rows = array();

      // stuff from parameters
      $this->q_row['ownerID'] = $ownerid;

      // general stuff that needs to be done for every qtype
      $this->q_row['creation_date'] = date("Y-m-d H:i:s");
      $this->q_row['last_edited'] = date("Y-m-d H:i:s");
      $this->q_row['q_type'] = $question->type;

      $this->q_row['status'] = isset($this->statuses[$question->status]) ? $this->statuses[$question->status] : $this->default_status;

      $this->q_row['theme'] = $question->theme;
      $this->q_row['notes'] = $question->notes;
      $this->q_row['leadin'] = $question->leadin;

      $this->q_row['bloom'] = $question->bloom;

      $this->q_row['q_media'] = $question->media;
      $this->q_row['q_media_width'] = $question->media_width;
      $this->q_row['q_media_height'] = $question->media_height;

      $this->q_row['deleted'] = null;
      $this->q_row['locked'] = null;
      $this->q_row['std'] = null;

      $this->q_row['q_option_order'] = $question->q_option_order;

      if(isset($question->settings)) {
        $this->q_row['settings']=$question->settings;
      }

      $oiii = print_r($question, true);
      $t = 8;
      if ($question->type == "blank") {
        $this->SaveBlank($question);
      } elseif ($question->type == "calculation") {
        $this->SaveCalculation($question);
        $this->q_row['q_type']='enhancedcalc';
      } elseif ($question->type == "dichotomous") {
        $this->SaveDichotomous($question);
      } elseif ($question->type == "extmatch") {
        $this->SaveExtMatch($question);
      } elseif ($question->type == "flash") {
        $this->SaveFlash($question);
      } elseif ($question->type == "hotspot") {
        $this->SaveHotspot($question);
      } elseif ($question->type == "info") {
        $this->SaveInfo($question);
      } elseif ($question->type == "labelling") {
        $this->SaveLabelling($question);
      } elseif ($question->type == "likert") {
        $this->SaveLikert($question);
      } elseif ($question->type == "matrix") {
        $this->SaveMatrix($question);
      } elseif ($question->type == "mcq") {
        $this->SaveMcq($question);
      } elseif ($question->type == "true_false") {
        $this->SaveTrueFalse($question);
      } elseif ($question->type == "mrq") {
        $this->SaveMrq($question);
      } elseif ($question->type == "rank") {
        $this->SaveRank($question);
      } elseif ($question->type == "textbox") {
        $this->SaveTextbox($question);
      } else {
        $this->AddError("Question type " . $question->type . " not yet supported", $question->load_id);
        continue;
      }

      if (!(in_array($this->q_row['q_option_order'], array('display order','alphabetic','random')))) {
        $this->q_row['q_option_order']='display order';
        print "correcting q_option_order";
      }
      if (!empty($this->q_row['scenario']) && strcasecmp("<p>&nbsp;</p>", $this->q_row['scenario']) == 0) $this->q_row['scenario'] = '';

      // create plain version of scenario and leadin
      $this->q_row['scenario_plain'] = (empty($this->q_row['scenario'])) ? '' : trim(strip_tags($this->q_row['scenario']));
      $this->q_row['leadin_plain'] = (empty($this->q_row['leadin'])) ? '' : trim(strip_tags($this->q_row['leadin']));

      if (!empty($this->q_row['correct_fback']) && !empty($this->q_row['incorrect_fback']) && $this->q_row['correct_fback'] == $this->q_row['incorrect_fback']) $this->q_row['incorrect_fback'] = '';

      // if no o_row, create a blank one
      if (count($this->o_rows) == 0 and $question->type != "calculation") {
        $this->o_row['marks_correct'] = 1;
        $this->o_row['marks_incorrect'] = 0;
        $this->o_row['marks_partial'] = 0;
        $this->o_rows[] = $this->o_row;
      }
      // store question row
      $this->db->InsertRow("questions", "q_id", $this->q_row);
      $question->save_id = $this->q_row['q_id'];

      $this->qm_row =$this->db->GetBlankTableRow("questions_modules");
      $this->qm_row['q_id'] = $this->q_row['q_id'];
      if(is_array($module_id)) {
        foreach (array_keys($module_id) as $mod_id) {
          $this->qm_row['idMod']=$mod_id;
          $this->db->InsertRow("questions_modules", "temp", $this->qm_row);
        }
      }else {
      $this->qm_row['idMod']=$module_id;
      $this->db->InsertRow("questions_modules", "temp", $this->qm_row);
      }
      $new_keywords = array();
      if ($module_id != -1) {
        if (is_array($module_id)) {
          $user_keywords2 = array();
          foreach (array_keys($module_id) as $mod_id) {
            $new_keywords1 = $this->SaveKeywords($this->q_row['q_id'], $question->keywords, $mod_id, $user_keywords, $user_keywords2);
            $new_keywords = array_merge($new_keywords, $new_keywords1);
          }
          $user_keywords = array_merge($user_keywords, $user_keywords2);
        } else {
          $new_keywords = $this->SaveKeywords($this->q_row['q_id'], $question->keywords, $module_id, $user_keywords);
        }
      }

      // store option rows
      foreach ($this->o_rows as & $o_row) {
        $o_row['o_id'] = $this->q_row['q_id'];
        if (!empty($o_row['feedback_right']) && $o_row['feedback_right'] == $o_row['feedback_wrong']) $o_row['feedback_wrong'] = "";
        $this->db->InsertRow("options", "id_num", $o_row);
      }

      // store additional metadata
      if ($question->load_id != '') {
        $meta_row = array('id' => null, 'questionID' => $question->save_id, 'type' => 'QTI Ident', 'value' => $question->load_id);
      }
      $this->db->InsertRow("questions_metadata", "id", $meta_row);

      echo "<h4>{$string['questiontables']}</h4>";
      echo "<div>{$string['questionsrow']}</div>";
      print_p($this->q_row, false);
      echo "<div>{$string['optionsrows']}</div>";
      print_p($this->o_rows, false, 100);
      echo "<div>{$string['newkeywords']}</div>";
      print_p($new_keywords, false);

      $track = array();
      $track['type'] = $string['qtiimport'];
      $track['typeID'] = $this->q_row['q_id'];
      $track['editor'] = $userID;
      $track['new'] = "{$string['imported1_2']} " . $params->original_filename;
      $track['part'] = "all";
      $track['changed'] = date("Y-m-d H:i:s");

      $db->InsertRow("track_changes", "id", $track);

      // we have a paper, add this question onto the list of questions for the paper
      if ($paperid && empty($data->papers)) {
        $p_row = $this->db->GetBlankTableRow('papers');

        $p_row['paper'] = $paperid;
        $p_row['question'] = $question->save_id;
        $p_row['screen'] = $nextscreen;
        $p_row['display_pos'] = $nextid++;

        $this->db->InsertRow('papers', 'p_id', $p_row);
      }
    }

    $logger = new Logger($mysqli);
    
    if (!empty($data->papers)) {
      foreach ($data->papers as & $paper) {
        foreach ($paper->screens as & $screen) {
          foreach ($screen->question_ids as $q_id) {
            $p_row = $this->db->GetBlankTableRow('papers');
            echo sprintf($string['addingquestiondetails'], $q_id, $nextid, $nextscreen) . '<br>';

            $p_row['paper'] = $paperid;
            $q = FindQuestion($data->questions, $q_id);
            $p_row['question'] = $q->save_id;
            $p_row['screen'] = $nextscreen;
            $p_row['display_pos'] = $nextid++;
            $this->db->InsertRow('papers', 'p_id', $p_row);
            
            $logger->track_change('Paper', $paperid, $userID, '', $q_id, 'Add Question (from QTI)');
          }
          $nextscreen++;
        }
      }

    }
  }

  function SaveBlank($question) {
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['display_method'] = $question->displaymode;
    $this->q_row['score_method'] = 'Mark per Option';

    $q_text = "";
    foreach ($question->question as $part) {
      if (substr($part, 0, 1) == "%") {
        $q_text .= "[blank]";

        $blankbit = $question->options[$part];

        $blanks = array();

        // get correct answer first
        foreach ($blankbit as $blank) {
          if ($blank->correct) $blanks[] = $blank->display;
        }

        // now add incorrect ones
        foreach ($blankbit as $blank) {
          if (!$blank->correct) $blanks[] = $blank->display;
        }
        $q_text .= implode(",", $blanks);

        $q_text .= "[/blank]";
      } else {
        $q_text .= $part;
      }
    }

    $o_row = $this->db->GetBlankTableRow("options");

    $o_row['option_text'] = $q_text;
    $o_row['marks_correct'] = $question->marks_correct;
    $o_row['marks_incorrect'] = $question->marks_incorrect;
    $o_row['marks_partial'] = $question->marks_partial;
    $this->o_rows[] = $o_row;

  }

  function SaveCalculation($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['display_method'] = $question->decimals . ",0," . $question->tolerance . "," . $question->units;
    $this->q_row['score_method'] = $question->score_method;

  }


  function SaveDichotomous($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['score_method'] = $question->score_method;
    $this->q_row['display_method'] = $question->display_method;

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->text;
      $o_row['correct'] = 'f';
      if ($option->iscorrect) $o_row['correct'] = 't';

      $o_row['feedback_right'] = $option->fb_correct;
      $o_row['feedback_wrong'] = $option->fb_incorrect;
      $o_row['marks_correct'] = $option->marks_correct;
      $o_row['marks_incorrect'] = $option->marks_incorrect;
      $o_row['marks_partial'] = 0;
      $o_row['o_media'] = $option->media;
      $o_row['o_media_width'] = $option->media_width;
      $o_row['o_media_height'] = $option->media_height;

      $this->o_rows[] = $o_row;
    }
  }

  function SaveExtMatch($question) {
    $scenario_text = "";
    $feedback = "";
    $answer_text = "";

    $media = $question->media . "|";
    $media_width = $question->media_width . "|";
    $media_height = $question->media_height . "|";

    $count = 0;

    foreach ($question->scenarios as $scenario) {
      $scenario_text .= $scenario->stem . "|";
      $feedback .= $scenario->feedback . "|";
      $answer_text .= implode("$", $scenario->correctans) . "|";

      $media .= $scenario->media . "|";
      $media_width .= $scenario->media_width . "|";
      $media_height .= $scenario->media_height . "|";
      $count++;
    }

    $scenario_text = substr($scenario_text, 0, strlen($scenario_text) - 1);
    $feedback = substr($feedback, 0, strlen($feedback) - 1);
    $answer_text = substr($answer_text, 0, strlen($answer_text) - 1);

    for ($i = $count; $i < 10; $i++) {
      $media .= '|';
      $media_width .= '|';
      $media_height .= '|';
    }

    $this->q_row['scenario'] = $scenario_text;
    $this->q_row['correct_fback'] = $feedback;
    $this->q_row['score_method'] = 'Mark per Option';
    $this->q_row['display_method'] = '';

    $this->q_row['q_media'] = $media;
    $this->q_row['q_media_width'] = $media_width;
    $this->q_row['q_media_height'] = $media_height;

    foreach ($question->optionlist as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->option;
      $o_row['correct'] = $answer_text;
      $o_row['marks_correct'] = $question->marks_correct;
      $o_row['marks_incorrect'] = $question->marks_incorrect;
      $o_row['marks_partial'] = $question->marks_partial;

      $this->o_rows[] = $o_row;
    }
  }

  function SaveFlash($question) {
    $this->q_row['q_media'] = $question->question_swf;
    $this->q_row['q_media_width'] = $question->question_swf_width;
    $this->q_row['q_media_height'] = $question->question_swf_height;

    $o_row = $this->db->GetBlankTableRow("options");

    $o_row['o_media'] = $question->feedback_swf;
    $o_row['o_media_width'] = $question->feedback_swf_width;
    $o_row['o_media_height'] = $question->feedback_swf_height;
    $o_row['marks_correct'] = $question->marks;

    $this->o_rows[] = $o_row;
  }

  function SaveHotspot($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['score_method'] = $question->score_method;

    $hs_text = "";
    foreach ($question->hotspots as $id => $hotspot) {
      $hs_text .= $hotspot->type . ";";
      $coords = array();
      foreach ($hotspot->coords as $coord) {
        $coords[] = dechex($coord);
      }
      $hs_text .= implode(",", $coords) . ";";
      $hs_text .= $id . ";";
    }

    $o_row = $this->db->GetBlankTableRow("options");

    $o_row['correct'] = $hs_text;

    // if rogo->qti->rogo, then use the raw text from the options table to make 1:1
    if ($question->raw_option) $o_row['correct'] = $question->raw_option;
    $o_row['marks_correct'] = $question->marks_correct;
    $o_row['marks_incorrect'] = $question->marks_incorrect;
    $o_row['marks_partial'] = $question->marks_partial;

    $this->o_rows[] = $o_row;

  }

  function SaveInfo($question) {
    $this->q_row['leadin'] = $question->leadin;
  }

  function SaveLabelling($question) {
    // 1 - 3/4 pt
    // 2 - 1 pt
    // 3 - 1 1/4 pt
    // 4 - 2 1/4 pt
    // 5 - 3 pt
    // 6 - 4 1/2 pt
    // 7 - 6 pt
    $line_thicknesses = array();
    $line_thicknesses["0.75"] = 1;
    $line_thicknesses["1"] = 2;
    $line_thicknesses["1.25"] = 3;
    $line_thicknesses["2.25"] = 4;
    $line_thicknesses["3"] = 5;
    $line_thicknesses["4.5"] = 6;
    $line_thicknesses["6"] = 7;

    $base = array();
    $base[0] = "0$0$8$30$";
    $base[1] = "1$0$8$77$";
    $base[2] = "2$0$8$125$";
    $base[3] = "3$0$8$172$";
    $base[4] = "4$0$8$220$";
    $base[5] = "5$0$8$267$";
    $base[6] = "6$0$110$315$";
    $base[7] = "7$0$110$362$";
    $base[8] = "8$0$8$410$";
    $base[9] = "9$0$8$457$";
    $base[10] = "10$0$110$30$";
    $base[11] = "11$0$110$77$";
    $base[12] = "12$0$110$125$";
    $base[13] = "13$0$110$172$";
    $base[14] = "14$0$110$220$";
    $base[15] = "15$0$110$267$";
    $base[16] = "16$0$110$315$";
    $base[17] = "17$0$110$362$";
    $base[18] = "18$0$110$410$";
    $base[19] = "19$0$110$457$";

    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['score_method'] = 'Mark per Option';

    $lt = $line_thicknesses[(string)$question->line_thickness];
    if ($lt == "") $lt = 1;
    $data = $question->line_color . ";" . $lt . ";" . $question->box_color . ";" . $question->font_size . ";" . $question->font_color . ";" . $question->width . ";" . $question->height . ";" . $question->label_type . ";";

    $count = 0;
    if (isset($question->labels)) {
      foreach ($question->labels as $id => $label) {
        //print_p($label);
        if ($label->left == -1 || $label->top == -1) {
          $base[$id] .= $label->tag;
        } else {
          $base[$id] = $id . "$0$" . ($label->left + 220) . "$" . ($label->top + 25) . "$" . $label->tag;
        }
      }
    }

    //print_p($base);
    $data .= implode("|", $base) . "|;";

    foreach ($question->arrows as $id => $arrow) {
      $id++;
      $data .= $id . "$" . $arrow->type . "$" . implode("$", $arrow->coords) . ";";
    }

    $o_row = $this->db->GetBlankTableRow("options");
    $o_row['correct'] = $data;
    if ($question->raw_option) $o_row['correct'] = $question->raw_option;

    $o_row['marks_correct'] = $question->marks_correct;
    $o_row['marks_incorrect'] = $question->marks_incorrect;
    $o_row['marks_partial'] = $question->marks_partial;

    $this->o_rows[] = $o_row;

  }

  function SaveLikert($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['score_method'] = 'Mark per Option';
    $this->q_row['display_method'] = implode("|", $question->scale);
    if ($question->hasna) {
      $this->q_row['display_method'] .= "|true";
    } else {
      $this->q_row['display_method'] .= "|false";
    }
  }

  function SaveMatrix($question) {
    $scenario_text = "";
    $answer_text = "";
    foreach ($question->scenarios as $scenario) {
      $scenario_text .= $scenario->scenario . "|";
      $answer_text .= $scenario->answer . "|";
    }

    $scenario_text = substr($scenario_text, 0, strlen($scenario_text) - 1);
    $answer_text = substr($answer_text, 0, strlen($answer_text) - 1);

    $this->q_row['scenario'] = $scenario_text;

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option;
      $o_row['correct'] = $answer_text;
      $o_row['marks_correct'] = $question->marks_correct;
      $o_row['marks_incorrect'] = $question->marks_incorrect;
      $o_row['marks_partial'] = $question->marks_partial;

      $this->o_rows[] = $o_row;
    }
  }

  function SaveMcq($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = (!empty($question->feedback)) ? $question->feedback : '';
    $this->q_row['q_option_order'] = $question->presentation;
    $this->q_row['score_method'] = 'Mark per Question';
    $this->q_row['display_method'] = 'vertical';

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->stem;
      $o_row['correct'] = $question->correct;

      $o_row['marks_correct'] = $option->marks_correct;
      $o_row['marks_incorrect'] = $option->marks_incorrect;
      $o_row['marks_partial'] = 0;

      $o_row['o_media'] = $option->media;
      $o_row['o_media_width'] = $option->media_width;
      $o_row['o_media_height'] = $option->media_height;

      $o_row['feedback_right'] = $option->fb_correct;
      $o_row['feedback_wrong'] = $option->fb_incorrect;

      $this->o_rows[] = $o_row;
    }

  }


  function SaveTrueFalse($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = (!empty($question->feedback)) ? $question->feedback : '';
    $this->q_row['q_option_order'] = $question->presentation;
    $this->q_row['score_method'] = 'Mark per Question';
    $this->q_row['display_method'] = 'vertical';
    $this->q_row['q_option_order'] = 'display order';

    $this->q_row['correct_fback'] = $question->fb_correct;
    $this->q_row['incorrect_fback'] = $question->fb_incorrect;

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->stem;
      $o_row['correct'] = $question->correct;

      $o_row['marks_correct'] = $option->marks_correct;
      $o_row['marks_incorrect'] = $option->marks_incorrect;
      $o_row['marks_partial'] = 0;

      $o_row['o_media'] = $option->media;
      $o_row['o_media_width'] = $option->media_width;
      $o_row['o_media_height'] = $option->media_height;

      $o_row['feedback_right'] = $question->fb_correct;
      $o_row['feedback_wrong'] = $question->fb_incorrect;


      $this->o_rows[] = $o_row;
    }


  }


  function SaveMrq($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['q_option_order'] = $question->score_method;
    $this->q_row['score_method'] = 'Mark per Option';

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->stem;
      $o_row['correct'] = 'n';
      $o_row['marks_correct'] = $option->marks_correct;
      $o_row['marks_incorrect'] = $option->marks_incorrect;
      $o_row['marks_partial'] = 0;
      if ($option->is_correct) {
        $o_row['correct'] = 'y';
      }
      $o_row['feedback_right'] = $option->fb_correct;
      $o_row['feedback_wrong'] = $option->fb_incorrect;
      $o_row['o_media'] = $option->media;
      $o_row['o_media_width'] = $option->media_width;
      $o_row['o_media_height'] = $option->media_height;

      $this->o_rows[] = $o_row;
    }
  }

  function SaveRank($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->fb_correct;
    $this->q_row['incorrect_fback'] = $question->fb_incorrect;
    $this->q_row['score_method'] = $question->score_method;

    foreach ($question->options as $option) {
      $o_row = $this->db->GetBlankTableRow("options");

      $o_row['option_text'] = $option->stem;
      $o_row['correct'] = intval($option->order);
      $o_row['marks_correct'] = $question->marks_correct;
      $o_row['marks_incorrect'] = $question->marks_incorrect;
      $o_row['marks_partial'] = $question->marks_partial;

      $this->o_rows[] = $o_row;
    }
    //$this->AddError("Question type " . $question->type . " not yet supported",$question->load_id);
  }

  function SaveTextBox($question) {
    $this->q_row['scenario'] = $question->scenario;
    $this->q_row['correct_fback'] = $question->feedback;
    $this->q_row['display_method'] = $question->columns . "x" . $question->rows;
    $this->q_row['score_method'] = 'Mark per Option';

    $o_row = $this->db->GetBlankTableRow("options");

    $o_row['option_text'] = $question->editor;
    $o_row['correct'] = implode(";", $question->terms);
    $o_row['marks_correct'] = $question->marks_correct;
    $o_row['marks_incorrect'] = $question->marks_incorrect;
    $o_row['marks_partial'] = 0;
    $this->o_rows[] = $o_row;
  }

  function GetExistingKeywords($module_id) {
    // We'll keep the keywords cached in an array and build it up as we add new keywords
    $user_keywords = array();

    $this->db->SetTable('keywords_user');
    $this->db->AddField('id');
    $this->db->AddField('keyword');
    $this->db->AddWhere('userID', $module_id, 'i');
    $this->db->AddWhere('keyword_type', 'team', 's');
    $t_kwds = $this->db->GetMultiRow();

    if (count($t_kwds) > 0) {
      for ($i = 0; $i < count($t_kwds); $i++) {
        $user_keywords['mod' . $module_id][$t_kwds[$i]['keyword']][] = $t_kwds[$i]['id'];
      }
    }

    return $user_keywords;
  }

  /**
   * Save the keywords for a question in a user's personal keywords
   * if they don't already exist
   *
   * @param int $q_id
   * @param array $q_keywords
   * @param int $userID
   * @param array $user_keywords
   */
  function SaveKeywords($q_id, $q_keywords, $moduleID, &$user_keywords, &$user_keywords2 = NULL) {
    $new_keywords = array();
    echo "savekeywrds<br />";

    // Loop through the keywords, saving against the user and question
    for ($i = 0; $i < count($q_keywords); $i++) {
      $kw_id = -1;

      // Exclude existing keywords from the list that we want to save
      if (!in_array($q_keywords[$i], array_keys($user_keywords['mod' . $moduleID]))) {
        // Add keyword to this user's list
        $ku_row = array('userID' => $moduleID, 'keyword' => $q_keywords[$i], 'keyword_type' => 'team');
        $this->db->InsertRow('keywords_user', 'id', $ku_row);
        $kw_id = $ku_row['id'];

        $new_keywords[$q_keywords[$i]] = $kw_id;
      } else {
        $kw_id = $user_keywords['mod' . $moduleID][$q_keywords[$i]][0];
      }

      // Add keyword to the keyword question link table
      if ($kw_id != -1) {
       // if(is_array($kw_id))
        $kq_row = array('q_id' => $q_id, 'keywordID' => $kw_id);
        //$kq_row=$kq_row[0];
        $this->db->InsertRow('keywords_question', '', $kq_row);
      }
    }

    if(!is_null($user_keywords2)) {
      $user_keywords2 = array_merge($user_keywords2, $new_keywords);
    } else {
      $user_keywords = array_merge($user_keywords, $new_keywords);
    }



    return $new_keywords;
  }

  public function setStatuses($statuses) {
    $this->statuses = $statuses;
  }

  public function setDefaultStatus($sid) {
    $this->default_status = $sid;
  }
}

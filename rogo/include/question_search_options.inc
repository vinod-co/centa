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
* Sidebar menu with question search options.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require_once '../classes/schoolutils.class.php';
require_once '../classes/searchutils.class.php';
require_once '../classes/stateutils.class.php';

$state = $stateutil->getState($configObject->get('cfg_root_path') . '/question/search.php');

?>
<script type="text/javascript" src="../js/state.js"></script>
<script>
  function getLastID(IDs) {
    var id_list = IDs.split(",");
    last_elm = id_list.length - 1;

    return id_list[last_elm];
  }

  function previewQ() {
    notice=window.open("../question/view_question.php?q_id=" + getLastID($('#questionID').val()) + "","preview","left=10,top=10,width=800,height=600,scrollbars=yes,toolbar=no,location=no,directories=no,status=yes,menubar=no,resizable");
  }
	
  function ed() {
    var loc = '../question/edit/index.php?q_id=' + getLastID($('#questionID').val());
    if ($('#qType').val() == 'random' || $('#qType').val() == 'keyword_based') {
      loc += '&type=' + $('#qType').val();
    }
    document.location = loc;
  }

  function deleteQuestion() {
    notice=window.open("../delete/check_delete_q_original.php?q_id=" + $('#questionID').val() + "&divID=" + $('#oldQuestionID').val() + "","notice","width=500,height=200,scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    notice.moveTo(screen.width/2-250, screen.height/2-100);
    if (window.focus) {
      notice.focus();
    }
  }

  function questionInfo() {
    notice=window.open("../question/info.php?q_id=" + getLastID($('#questionID').val()) + "","question_info","width=700,height=600,scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    notice.moveTo(screen.width/2-250, screen.height/2-250);
    if (window.focus) {
      notice.focus();
    }
  }

  function copyToPaper() {
    notice=window.open("../question/copy_onto_paper.php?q_id=" + $('#questionID').val() + "","notice","width=600,height=" + (screen.height-50) + ",scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    notice.moveTo(screen.width/2-300, 10);
    if (window.focus) {
      notice.focus();
    }
  }

  function linkToPaper() {
    notice=window.open("../question/link_to_paper.php?q_id=" + $('#questionID').val() + "","linktopaper","width=600,height=" + (screen.height-50) + ",scrollbars=yes,toolbar=no,location=no,directories=no,status=yes,menubar=no,resizable");
    notice.moveTo(screen.width/2-300, 10);
    if (window.focus) {
      notice.focus();
    }
  }

  function updateMenu(ID) {
    $('#menu' + ID).toggle();

    <?php
      echo "icon = (document.getElementById('icon' + ID).getAttribute('src').indexOf('down_arrow_icon.gif')!=-1) ? '{$configObject->get('cfg_root_path')}/artwork/up_arrow_icon.gif' : '{$configObject->get('cfg_root_path')}/artwork/down_arrow_icon.gif';\n";
    ?>
    alttag = ($('#icon' + ID).attr('alt') == 'Hide') ? 'Show' : 'Hide';
    $('#icon' + ID).attr('src', icon);
    $('#icon' + ID).attr('alt', alttag);

 		updateState('menu' + ID, $('#menu' + ID).css('display'));

  }

  function updateDropdownState(mySel, NameOfState) {
    setting = mySel.options[mySel.selectedIndex].value;
    updateState(NameOfState, setting);
  }

  function selectDateRadio() {
    $('#question_date5').attr('checked', true);
  }

</script>

<div id="left-sidebar" class="sidebar">
<form id="PapersMenu" name="PapersMenu"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">

<table cellpadding="4" cellspacing="0" border="0" style="width:210px">
<tr><td>
<div><strong><?php echo $string['wordorphrase']; ?></strong><br /><input type="text" name="searchterm" id="searchterm" size="25" style="width:98%" value="<?php if (isset($_GET['searchterm'])) echo $_GET['searchterm']; ?>" /></div>
<div><strong><?php echo $string['questiontype']; ?></strong><br /><select name="searchtype" style="max-width:195px">
<option value="%"><?php echo $string['alltypes']; ?></option>
<?php
  $qTypes = array('area'=>$string['area'],'enhancedcalc'=>$string['calculation'],'dichotomous'=>$string['dichotomous'],'extmatch'=>$string['extmatch'],'blank'=>$string['blank'],'flash'=>$string['flash'],'hotspot'=>$string['hotspot'],'info'=>$string['info'],'labelling'=>$string['labelling'],'likert'=>$string['likert'],'matrix'=>$string['matrix'],'mcq'=>$string['mcq'],'mrq'=>$string['mrq'],'rank'=>$string['rank'],'sct'=>$string['sct'],'textbox'=>$string['textbox'],'true_false'=>$string['true_false']);
  foreach ($qTypes as $tmp_type => $tmp_title) {
    if (isset($_GET['searchtype']) and $_GET['searchtype'] == $tmp_type) {
      echo "<option value=\"$tmp_type\" selected>$tmp_title</option>\n";
    } else {
      echo "<option value=\"$tmp_type\">$tmp_title</option>\n";
    }
  }
?>
</select></div>
<?php
  if (isset($state['checkbox10']) and $state['checkbox10'] == 'true') {
    echo "<input class=\"chk\" type=\"checkbox\" id=\"checkbox10\" name=\"locked\" value=\"1\" checked=\"checked\" />";
  } else {
    echo "<input class=\"chk\" type=\"checkbox\" id=\"checkbox10\" name=\"locked\" value=\"1\" />";
  }
	echo ' <label for="checkbox10">' . $string['inclockedquestions'] . '</label>';
?>
<br />

  <table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr><td><a href="#" style="font-weight:bold; color:black" onclick="updateMenu(8);"><?php echo $string['questionsections'] ?></a></td>
  <td align="right"><a href="#" onclick="updateMenu(8)"><?php
    if (isset($state['menu8']) and $state['menu8'] == 'block') {
      echo "<img id=\"icon8\" src=\"../artwork/up_arrow_icon.gif\" width=\"10\" height=\"9\" alt=\"Hide\" />";
    } else {
      echo "<img id=\"icon8\" src=\"../artwork/down_arrow_icon.gif\" width=\"10\" height=\"9\" alt=\"Show\" />";
    }
  ?></a></td></tr>
  </table>

<?php
  if (isset($state['menu8'])) {
    echo "<div id=\"menu8\" style=\"margin-left:15px; display:" . $state['menu8'] . "\">\n";
  } else {
    echo "<div id=\"menu8\" style=\"margin-left:15px; display:none\">\n";
  }
  if (isset($state['checkbox1']) and $state['checkbox1'] == 'false') {
    $checked = '';
  } else {
    $checked = ' checked="checked"';
  }
	echo "<div><input class=\"chk\" type=\"checkbox\" id=\"checkbox1\" name=\"theme\"$checked /> <label for=\"checkbox1\">" . $string['theme'] . "</label></div>\n";
	
  if (isset($state['checkbox2']) and $state['checkbox2'] == 'false') {
    $checked = '';
  } else {
    $checked = ' checked="checked"';
  }
	echo "<div><input class=\"chk\" type=\"checkbox\" id=\"checkbox2\" name=\"scenario\"$checked /> <label for=\"checkbox2\">" . $string['scenario'] . "</label></div>\n";

  if (isset($state['checkbox3']) and $state['checkbox3'] == 'false') {
    $checked = '';
  } else {
    $checked = ' checked="checked"';
  }
	echo "<div><input class=\"chk\" type=\"checkbox\" id=\"checkbox3\" name=\"leadin\"$checked /> <label for=\"checkbox3\">" . $string['leadin'] . "</label></div>\n";

  if (isset($state['checkbox4']) and $state['checkbox4'] == 'false') {
    $checked = '';
  } else {
    $checked = ' checked="checked"';
  }
	echo "<div><input class=\"chk\" type=\"checkbox\" id=\"checkbox4\" name=\"options\"$checked /> <label for=\"checkbox4\">" . $string['options'] . "</label></div>\n";
?>
</div>

  <table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr><td><a href="#" style="font-weight:bold; color:black" onclick="updateMenu(6);"><?php echo $string['whenmodified']; ?></a></td>
  <td align="right"><a href="#" onclick="updateMenu(6);"><?php
    if (isset($state['menu6']) and $state['menu6'] == 'table') {
      echo '<img id="icon6" src="../artwork/up_arrow_icon.gif" width="10" height="9" alt="Hide" />';
    } else {
      echo '<img id="icon6" src="../artwork/down_arrow_icon.gif" width="10" height="9" alt="Show" />';
    }
  ?></a></td></tr>
  </table>

<?php
  if (isset($state['menu6'])) {
    echo "<table id=\"menu6\" style=\"margin-left:12px; display:" . $state['menu6'] . "\">\n";
  } else {
    echo "<table id=\"menu6\" style=\"margin-left:12px; display:none\">\n";
  }
  echo '<tr><td colspan="2">';

  $date_options = array("dont remember"=>$string['dont remember'], "week"=>$string['week'], "month"=>$string['month'], "year"=>$string['year'], "specify"=>$string['specify']);
  $i = 1;
  foreach ($date_options as $date_value=>$date_label) {
    if ((isset($_GET['question_date']) and $_GET['question_date'] == $date_value) or (!isset($_GET['question_date']) and $date_value == 'dont remember')) {
      echo "<div><input type=\"radio\" id=\"question_date$i\" name=\"question_date\" value=\"$date_value\" checked />" . $string[$date_value] . "</div>";
    } else {
      echo "<div><input type=\"radio\" id=\"question_date$i\" name=\"question_date\" value=\"$date_value\" />" . $string[$date_value] . "</div>";
    }
    $i++;
  }
?>
</td></tr>

<tr><td style="text-align:right"><?php echo $string['from']; ?>&nbsp;</td><td>
<?php
  if (isset($_GET['fday'])) {
    $target = $_GET['fday'];
  } else {
    $target = 0;
  }
  echo "<select name=\"fday\" onchange=\"selectDateRadio()\">\n";
  for ($i=1; $i<=31; $i++) {
    if ($i < 10) {
      if ($i == $target) {
        echo "<option value=\"0$i\" selected>0$i</option>\n";
      } else {
        echo "<option value=\"0$i\">0$i</option>\n";
      }
    } else {
      if ($i == $target) {
        echo "<option value=\"$i\" selected>$i</option>\n";
      } else {
        echo "<option value=\"$i\">$i</option>\n";
      }
    }
  }
  if (isset($_GET['fmonth'])) {
    $target = $_GET['fmonth'];
  } else {
    $target = 0;
  }
  echo "</select><select name=\"fmonth\" onchange=\"selectDateRadio()\">\n";
  for ($i=1; $i<=12; $i++) {
    if ($i < 10) {
      if ($i == $target) {
        echo "<option value=\"0$i\" selected>0$i</option>\n";
      } else {
        echo "<option value=\"0$i\">0$i</option>\n";
      }
    } else {
      if ($i == $target) {
        echo "<option value=\"$i\" selected>$i</option>\n";
      } else {
        echo "<option value=\"$i\">$i</option>\n";
      }
    }
  }
  echo "</select><select name=\"fyear\" onchange=\"selectDateRadio()\">\n";
  for ($i=1999; $i<=date("Y"); $i++) {
    if (isset($_GET['fyear']) and $_GET['fyear'] == $i) {
      echo "<option value=\"$i\" selected>$i</option>\n";
    } else {
      echo "<option value=\"$i\">$i</option>\n";
    }
  }
?>
</select>
</td></tr>
<tr><td style="text-align:right"><?php echo $string['to']; ?>&nbsp;</td><td>
<?php
  if (isset($_GET['tday'])) {
    $target = $_GET['tday'];
  } else {
    $target = date("d");
  }
  echo "<select name=\"tday\" onchange=\"selectDateRadio()\">\n";
  for ($i=1; $i<=31; $i++) {
    if ($i < 10) {
      if ($i == $target) {
        echo "<option value=\"0$i\" selected>0$i</option>\n";
      } else {
        echo "<option value=\"0$i\">0$i</option>\n";
      }
    } else {
      if ($i == $target) {
        echo "<option value=\"$i\" selected>$i</option>\n";
      } else {
        echo "<option value=\"$i\">$i</option>\n";
      }
    }
  }
  if (isset($_GET['tmonth'])) {
    $target = $_GET['tmonth'];
  } else {
    $target = date("m");
  }
  echo "</select><select name=\"tmonth\" onchange=\"selectDateRadio()\">\n";
  for ($i=1; $i<=12; $i++) {
    if ($i < 10) {
      if ($i == $target) {
        echo "<option value=\"0$i\" selected>0$i</option>\n";
      } else {
        echo "<option value=\"0$i\">0$i</option>\n";
      }
    } else {
      if ($i == $target) {
        echo "<option value=\"$i\" selected>$i</option>\n";
      } else {
        echo "<option value=\"$i\">$i</option>\n";
      }
    }
  }
  if (isset($_GET['tyear'])) {
    $target = $_GET['tyear'];
  } else {
    $target = date("Y");
  }
  echo "</select><select name=\"tyear\" onchange=\"selectDateRadio()\">\n";
  for ($i=1999; $i<=date("Y"); $i++) {
    if ($i == $target) {
      echo "<option value=\"$i\" selected>$i</option>\n";
    } else {
      echo "<option value=\"$i\">$i</option>\n";
    }
  }
?>
</select>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>

  <table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr><td><a href="#" style="font-weight:bold; color: black" onclick="updateMenu(9);"><?php echo $string['metadata'] ?></a></td>
  <td align="right"><a href="#" onclick="updateMenu(9);"><?php
    if (isset($state['menu9']) and $state['menu9'] == 'block') {
      echo "<img id=\"icon9\" src=\"../artwork/up_arrow_icon.gif\" width=\"10\" height=\"9\" alt=\"Hide\" />";
    } else {
      echo "<img id=\"icon9\" src=\"../artwork/down_arrow_icon.gif\" width=\"10\" height=\"9\" alt=\"Show\" />";
    }
  ?></a></td></tr>
  </table>

<?php
  if (isset($state['menu9'])) {
    echo "<div id=\"menu9\" style=\"margin-left:15px; display:" . $state['menu9'] . "\">\n";
  } else {
    echo "<div id=\"menu9\" style=\"margin-left:15px; display:none\">\n";
  }

if (!isset($status_array)) {
  $status_array = QuestionStatus::get_all_statuses($mysqli, $string, true);
}
?>
  <div style="margin-left:4px"><?php echo $string['module']; search_utils::display_staff_modules_dropdown($userObject, $string, $mysqli); ?></div>
  <div style="margin-left:4px"><?php echo $string['owner']; search_utils::display_owners_dropdown($userObject, $mysqli, 'questions', $string, $state, 100); ?></div>
  <div style="margin-left:4px"><?php echo $string['status'].': '; search_utils::display_status($status_array, $state); ?></div>
  <div style="margin-left:4px"><?php echo $string['blooms'].': '; search_utils::display_blooms_dropdown($string, $state); ?></div>

  <div style="margin-left:4px"><?php echo $string['keyword'].': '; ?> <select style="max-width:185px" name="keywordID"><br />
  <option value=""></option>
<?php
  $old_moduleID = '';
  $stmt = $mysqli->prepare("SELECT moduleid, keyword, keywords_user.id FROM keywords_user, modules WHERE keywords_user.userID = modules.id AND moduleid IN ('" . implode("','", $staff_modules) . "') AND mod_deleted IS NULL AND active = 1 ORDER BY moduleid, keyword");
  $stmt->execute();
  $stmt->bind_result($moduleID, $keyword, $keywordID);
  while ($stmt->fetch()) {
    if ($old_moduleID != $moduleID) {
			if ($old_moduleID != '') echo "</optgroup>\n";
			echo "<optgroup label=\"$moduleID\">\n";
		}
    if (isset($_GET['keywordID']) and $_GET['keywordID'] == $keywordID) {
      echo "<option value=\"$keywordID\" selected>$keyword</option>\n";
    } else {
      echo "<option value=\"$keywordID\">$keyword</option>\n";
    }
		$old_moduleID = $moduleID;
  }
  if ($stmt->num_rows > 0) echo "</optgroup>\n";
  $stmt->close();

  echo "<optgroup label=\"Personal Keywords\">\n";
  $stmt = $mysqli->prepare("SELECT id, keyword FROM keywords_user WHERE userID = ? ORDER BY keyword");
  $stmt->bind_param('i', $userObject->get_user_ID());
  $stmt->execute();
  $stmt->bind_result($keywordID, $keyword);
  while ($stmt->fetch()) {
    if (isset($_GET['keywordID']) and $_GET['keywordID'] == $keywordID) {
      echo "<option value=\"$keywordID\" selected>$keyword</option>\n";
    } else {
      echo "<option value=\"$keywordID\">$keyword</option>\n";
    }
  }
  $stmt->close();
  echo "</optgroup>\n";
?>
</select></div>
</div>

<br />


<div style="text-align:center"><input class="ok" type="submit" name="submit" value="<?php echo $string['search'] ?>" /></div>
</td></tr>
</table>

<br />
<div class="submenuheading" id="banktasks"><?php echo $string['currentquestiontasks'] ?></div>

<div id="menu2a">
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/small_play_grey.png" alt="<?php echo $string['quickview']; ?>" /><?php echo $string['quickview']; ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/edit_grey.png" alt="<?php echo $string['editquestion']; ?>" /><?php echo $string['editquestion']; ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/information_icon_grey.gif" alt="<?php echo $string['information']; ?>" /><?php echo $string['information']; ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/copy_icon_grey.gif" alt="<?php echo $string['copyontopaperx']; ?>" /><?php echo $string['copyontopaperx']; ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/link_grey.png" alt="<?php echo $string['linktopaper']; ?>" /><?php echo $string['linktopaper']; ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/red_cross_grey.png" alt="<?php echo $string['deletequestion']; ?>" /><?php echo $string['deletequestion']; ?></div>
</div>

<div style="display:none" id="menu2b">
	<div class="menuitem"><a id="preview" onclick="previewQ(); return false;" href="#"><img class="sidebar_icon" src="../artwork/small_play.png" alt="<?php echo $string['quickview']; ?>" /><?php echo $string['quickview']; ?></a></div>
	<div class="menuitem"><a id="edit" onclick="ed(); return false;" href="#"><img class="sidebar_icon" src="../artwork/edit.png" alt="<?php echo $string['editquestion']; ?>" /><?php echo $string['editquestion']; ?></a></div>
	<div class="menuitem"><a id="information" href="#" onclick="questionInfo(); return false;"><img class="sidebar_icon" src="../artwork/information_icon.gif" alt="<?php echo $string['information']; ?>" /><?php echo $string['information']; ?></a></div>
	<div class="menuitem"><a id="copy" href="#" onclick="copyToPaper(); return false;"><img class="sidebar_icon" src="../artwork/copy_icon.gif" alt="<?php echo $string['copyontopaperx']; ?>" /><?php echo $string['copyontopaperx']; ?></a></div>
	<div class="menuitem"><a id="link" href="#" onclick="linkToPaper(); return false;"><img class="sidebar_icon" src="../artwork/link.png" alt="<?php echo $string['linktopaper']; ?>" /><?php echo $string['linktopaper']; ?></a></div>
	<div class="menuitem"><a id="delete" href="#" onclick="deleteQuestion(); return false;"><img class="sidebar_icon" src="../artwork/red_cross.png" alt="<?php echo $string['deletequestion']; ?>" /><?php echo $string['deletequestion']; ?></a></div>
</div>

<div style="display:none" id="menu2c">
	<div class="menuitem"><a id="preview" onclick="previewQ(); return false;" href="#"><img class="sidebar_icon" src="../artwork/small_play.png" alt="<?php echo $string['quickview']; ?>" /><?php echo $string['quickview']; ?></a></div>
	<div class="menuitem"><a id="edit" onclick="ed(); return false;" href="#"><img class="sidebar_icon" src="../artwork/edit.png" alt="<?php echo $string['editquestion']; ?>" /><?php echo $string['editquestion']; ?></a></div>
	<div class="menuitem"><a id="information" href="#" onclick="questionInfo(); return false;"><img class="sidebar_icon" src="../artwork/information_icon.gif" alt="<?php echo $string['information']; ?>" /><?php echo $string['information']; ?></a></div>
	<div class="menuitem"><a id="copy" href="#" onclick="copyToPaper(); return false;"><img class="sidebar_icon" src="../artwork/copy_icon.gif" alt="<?php echo $string['copyontopaperx']; ?>" /><?php echo $string['copyontopaperx']; ?></a></div>
	<div class="menuitem"><a id="link" href="#" onclick="linkToPaper(); return false;"><img class="sidebar_icon" src="../artwork/link.png" alt="<?php echo $string['linktopaper']; ?>" /><?php echo $string['linktopaper']; ?></a></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/red_cross_grey.png" alt="<?php echo $string['deletequestion']; ?>" /><?php echo $string['deletequestion']; ?></div>
</div>

<input type="hidden" name="questionID" id="questionID" value="" />
<input type="hidden" name="qType" id="qType" value="" />
<input type="hidden" name="screenNo" id="screenNo" value="" />
<input type="hidden" name="oldQuestionID" id="oldQuestionID" value="" />

</form>
</div>

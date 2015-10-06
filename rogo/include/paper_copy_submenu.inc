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
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require_once '../config/campuses.inc';
require_once '../classes/dateutils.class.php';
?>
<style>
	.cancel {margin-right:8px}
</style>

<script>
  function changeButton() {
    if ($("#paper_type").val() == '2') {
      $('#next_button').show();
      $('#submit_button').hide();
    } else {
      $('#submit_button').show();
      $('#next_button').hide();
    }
  }
  
  function nextTable() {
    var paper_name = $('#new_paper').val();
    $.post("../ajax/paper/check_name.php", {name:paper_name}, function(data, status) {
      if (data == 'unique') {
        $('#table1div').hide();
        $('#table2div').show();        
        $('#new_paper').subClass('errfield');
      } else {
        $('#new_paper').addClass('errfield');
        alert("<?php echo $string['namewarning'] ?>");
      }
    });
 
  }
  
  function checkCopyForm() {
    if ($("#paper_type").val() == '2') {
      if ($('#period').val() == '') {
        alert ("<?php echo $string['msg7'] ?>");
        return false;
      }
      
     if ($('#duration_hours').val() == '' || $('#duration_mins').val() == '') {
        alert ("<?php echo $string['msg8'] ?>");
        return false;
      }
      
      if ($('#cohort_size').val() == '') {
        alert ("<?php echo $string['msg9'] ?>");
        return false;
      }
    }
  }
	
  $(function () {
    $(".cancel").click(function(event) {
      $('#copy_submenu').hide();
      event.stopPropagation();
    });
  });
</script>

<div class="popup" style="z-index:20; font-size:90%; background-color:white; top:120px; width:630px; height:346px" id="copy_submenu" onclick="showCopyMenu(true, event);">
<?php
if ($configObject->get('cfg_summative_mgmt')) {
  echo '<form method="post" action="../paper/copy.php" onsubmit="return checkCopyForm()">';
} else {
  echo '<form method="post" action="../paper/copy.php">';
}
?>
<div id="table1div" style="width:100%">
<table cellpadding="1" cellspacing="0" border="0" style="font-size:100%; width:100%" id="table1">
<tr>
<td colspan="5" style="background-color:#F2B100; height:5px; padding:0"> </td>
</tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr>
<td style="text-align:right"><?php echo $string['copyname']; ?>&nbsp;</td>
<td colspan="4"><input type="text" id="new_paper" name="new_paper" maxlength="255" value="<?php echo $properties->get_paper_title() ?>" size="65" required />
<input type="hidden" name="paperID" value="<?php echo $paperID ?>" />
<input type="hidden" name="module" value="<?php echo $module ?>" />
<input type="hidden" name="folder" value="<?php echo $folder ?>" />
</td>
</tr>

<tr>
<td style="text-align:right"><?php echo $string['type'] ?>&nbsp;</td>
<td colspan="4"><select name="paper_type" id="paper_type" onchange="changeButton()">
<?php
  switch ($properties->get_paper_type()) {
    case 0:
    case 1:
    case 2:
      $types = array(0=>$string['formative self-assessment'], 1=>$string['progress test'], 2=>$string['summative exam']);
      break;
    case 3:
      $types = array(3=>$string['survey']);
      break;
    case 4:
      $types = array(4=>$string['osce station']);
      break;
    case 5:
      $types = array(5=>$string['offline paper']);
      break;
    case 6:
      $types = array(6=>$string['peer review']);
      break;
  }
  foreach ($types as $type_no=>$type_text) {
    if ($properties->get_paper_type() == $type_no) {
      echo "<option value=\"$type_no\" selected>$type_text</option>\n";
    } else {
      echo "<option value=\"$type_no\">$type_text</option>\n";
    }
  }
?>
  </select><td><tr>
<?php
  $next_flag = 1;
  echo '<tr><td style="text-align:right">' . $string['academicsession'] . '</td><td>';
  $module_details = $mysqli->prepare("SELECT DISTINCT calendar_year FROM modules_student WHERE calendar_year != '' ORDER BY calendar_year DESC");
  $module_details->execute();
  $module_details->bind_result($calendar_year);
  echo "<select name=\"session\">\n";
  if ($properties->get_paper_type() == '0' or $properties->get_paper_type() == '1') {
    echo "<option value=\"\">N/A</option>\n";
  }
  while ($module_details->fetch()) {
    if ($next_flag == 1) {
      $next_session = (substr($calendar_year,0,4) + 1) . '/' . (substr($calendar_year,-2) + 1);
      $sel = (date_utils::get_current_academic_year() == $next_session) ? ' selected="selected"' : '';
      echo "<option value=\"$next_session\"$sel>$next_session</option>\n";
      $next_flag = 0;
    }
    $sel = (date_utils::get_current_academic_year() == $calendar_year) ? ' selected="selected"' : '';
    echo "<option value=\"$calendar_year\"$sel>$calendar_year</option>\n";
  }
  echo "</select></td>\n";
?>
</tr>

<tr><td colspan="5">&nbsp;</td></tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;<input type="radio" name="copytype" value="paperonly" /><?php echo $string['paperonly'] ?></td>
<td>&nbsp;</td>
<td>&nbsp;<input type="radio" name="copytype" value="paperquestions" checked /><?php echo $string['paperandquestions'] ?></td>
<td>&nbsp;</td>
</tr>
<tr>
<td style="width:130px" align="center">&nbsp;</td>
<td style="width:170px" align="center"><img src="../artwork/copy_paper_only.png" width="120" height="130" /></td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td style="width:170px" align="center"><img src="../artwork/copy_paper_questions.png" width="120" height="130" /></td>
<td style="width:110px" align="center">&nbsp;</td>
</tr>
<tr>
<?php
if ($properties->get_paper_type() == '2' and $configObject->get('cfg_summative_mgmt')) {
  echo '<td align="center" colspan="5"><input type="button" class="cancel" value="' . $string['cancel'] . '" name="cancel" id="cancel" /><input type="button" class="ok" value="' . $string['next'] . '" name="next" onclick="nextTable();" id="next_button" /><input type="submit" class="ok" style="display:none" value="' . $string['copypaper'] . '" name="submit" id="submit_button" /></td>';
} else {
  echo '<td align="center" colspan="5"><input type="button" class="cancel" value="' . $string['cancel'] . '" name="cancel" id="cancel" /><input type="button" class="ok" style="display:none" value="' . $string['next'] . '" name="next" onclick="nextTable();" id="next_button" /><input type="submit" class="ok" value="' . $string['copypaper'] . '" name="submit" id="submit_button" onclick="checkName()" /></td>';
}
?>
</tr>
</table>
</div>

<div id="table2div" style="display:none; width:100%">
<table cellpadding="1" cellspacing="1" border="0" style="font-size:90%; width:100%" id="table2">
<tr>
<td colspan="6" style="background-color:#F2B100; height:5px; padding:0"> </td>
</tr>
<tr><td colspan="6">&nbsp;</td></tr>
<tr>
<?php
  $next_flag = 1;
  echo '<tr><td style="text-align:right">' . $string['campus'] . '</td><td><select name="campus">';
  foreach ($cfg_campus_list as $campus) {
    if ($campus == $cfg_campus_default) {
      echo "<option value=\"$campus\" selected>$campus</option>";
    } else {
      echo "<option value=\"$campus\">$campus</option>";
    }
  }
  echo '</select></td><td style="text-align:right">' . $string['barriersneeded'] . '</td><td><input type="checkbox" name="barriers_needed" value="1" chacked="checked" /><td style="text-align:right">' . $string['duration'] . '</td><td>';
	echo '<select name="duration_hours" id="duration_hours">';
	echo "<option value=\"\"></option>\n";
	for ($i=0; $i<=12; $i++) {
		echo "<option value=\"$i\">$i</option>\n";
	}
	echo '</select> ' . $string['hrs'] . ' <select name="duration_mins" id="duration_mins">';
	echo "<option value=\"\"></option>\n";
	for ($i=0; $i<60; $i++) {
		echo "<option value=\"$i\">$i</option>\n";
	}
	echo '</select> ' . $string['mins'] . '</td></tr>';
  echo '<tr><td style="text-align:right">' . $string['daterequired'] . '</td><td><select name="period" id="period">';
  $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
  echo "<option value=\"\"></option>\n";
  for ($i=0; $i<12; $i++) {
    echo "<option value=\"$i\">" . $string[$months[$i]] . "</option>\n";
  }
  echo '</select></td><td style="text-align:right">' . $string['cohortsize'] . '</td><td><select name="cohort_size" id="cohort_size">';
  echo "<option value=\"\"></option>\n";
  $sizes = array('&lt;whole cohort&gt', '0-10', '11-20', '21-30', '31-40', '41-50', '51-75', '76-100', '101-150', '151-200', '201-300', '301-400', '401-500');
  foreach ($sizes as $size) {
    echo "<option value=\"$size\">$size</option>\n";
  }
  echo '</select></td><td style="text-align:right">' . $string['sittings'] . '</td><td><select name="sittings">';
  for ($i=1; $i<=6; $i++) {
    echo "<option value=\"$i\">$i</option>";
  }
  echo '</select></td></tr>';

  echo '<tr><td colspan="6">&nbsp;</td></tr>';
  echo '<tr><td style="text-align:right">' . $string['notes'] . '</td><td colspan="5"><textarea style="width:98%; height:77px" cols="40" rows="5" name="notes" style="text-size:90%"></textarea></td></tr>';
  
  echo '<tr><td colspan="6">&nbsp;</td></tr>';
  echo '<tr><td colspan="6">&nbsp;</td></tr>';
  echo '<tr><td colspan="6" style="text-align:center"><input type="button" class="cancel" value="' . $string['cancel'] . '" name="cancel" id="cancel" onclick="" /><input type="submit" class="ok" value="' . $string['copypaper'] . '" name="submit" /></td></tr>';

?>
</table>
</div>

</form>
</div>
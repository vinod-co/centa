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

  if (isset($_GET['module'])) {
    $module = $_GET['module'];
  } else {
    $module = '';
  }
?>
<script>
  function newKeyword() {
    keywordwin=window.open("<?php echo $configObject->get('cfg_root_path') ?>/folder/new_keyword.php?module=<?php echo $module; ?>","keywords","width=350,height=120,left="+(screen.width/2-175)+",top="+(screen.height/2-60)+",scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    if (window.focus) {
      keywordwin.focus();
    }
  }

  function editKeyword() {
    keywordwin=window.open("<?php echo $configObject->get('cfg_root_path') ?>/folder/edit_keyword.php?keywordID=" + getLastID($('#keywordID').val()) + "&module=<?php echo $module; ?>","keywords","width=350,height=120,left="+(screen.width/2-175)+",top="+(screen.height/2-60)+",scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    if (window.focus) {
      keywordwin.focus();
    }
  }

  function deleteKeyword() {
    keywordwin=window.open("<?php echo $configObject->get('cfg_root_path') ?>/delete/check_delete_team_keyword.php?keywordID=" + $('#keywordID').val() + "&module=<?php echo $module; ?>","keywords","width=500,height=120,left="+(screen.width/2-250)+",top="+(screen.height/2-60)+",scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    if (window.focus) {
      keywordwin.focus();
    }
  }
</script>

<div id="left-sidebar" class="sidebar">
<form name="myform">
<div id="menu1a">
	<div class="menuitem"><a href="#" onclick="newKeyword(); return false;"><img class="sidebar_icon" src="../artwork/key.png" alt="key" /><?php echo $string['createnewkeyword'] ?></a></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/edit_grey.png" alt="" /><?php echo $string['editkeyword'] ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/red_cross_grey.png" alt="" /><?php echo $string['deletekeyword'] ?></div>
	<?php
		if (isset($keyword_list) and count($keyword_list) > 0) {
			echo "<div class=\"menuitem\"><a href=\"export_keywords.php?module=$module\"><img class=\"sidebar_icon\" src=\"../artwork/export_16.gif\" alt=\"Export\" />" . $string['exportkeywords'] . "</a></div>\n";
		} else {
			echo "<div class=\"grey menuitem\"><img class=\"sidebar_icon\" src=\"../artwork/export_grey_16.gif\" alt=\"Export\" />" . $string['exportkeywords'] . "</div>\n";
		}
	?>
	<div class="menuitem"><a href="import_keywords.php?module=<?php echo $module; ?>"><img class="sidebar_icon" src="../artwork/import_16.gif" alt="Import" /><?php echo $string['importkeywords'] ?></a></div>
</div>

<div style="display:none" id="menu1b">
	<div class="menuitem"><a href="#" onclick="newKeyword()"><img class="sidebar_icon" src="../artwork/key.png" alt="key" /><?php echo $string['createnewkeyword'] ?></a></div>
	<div class="menuitem"><a href="#" onclick="editKeyword(); return false;"><img class="sidebar_icon" src="../artwork/edit.png" alt="" /><?php echo $string['editkeyword'] ?></a></div>
	<div class="menuitem"><a href="#" onclick="deleteKeyword(); return false;"><img class="sidebar_icon" src="../artwork/red_cross.png" alt="" /><?php echo $string['deletekeyword'] ?></a></div>
	<div class="menuitem"><a href="export_keywords.php?module=<?php echo $module; ?>"><img class="sidebar_icon" src="../artwork/export_16.gif" alt="Export" /><?php echo $string['exportkeywords'] ?></a></div>
	<div class="menuitem"><a href="import_keywords.php?module=<?php echo $module; ?>"><img class="sidebar_icon" src="../artwork/import_16.gif" alt="Import" /><?php echo $string['importkeywords'] ?></a></div>
</div>

<input type="hidden" name="id" id="keywordID" value="" />
</form>
</div>

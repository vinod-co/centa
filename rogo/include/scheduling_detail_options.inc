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

?>
<script>
  function editProperties(paperID) {
    properties=window.open("<?php echo $configObject->get('cfg_root_path') ?>/paper/properties.php?paperID=<?php echo $paperid ?>&caller=scheduling&noadd=y","properties","width=888,height=650,left="+(screen.width/2-444)+",top="+(screen.height/2-325)+",scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    properties.focus();
  }
  
  function convtFormative() {
    notice=window.open("<?php echo $configObject->get('cfg_root_path') ?>/admin/check_convert_formative.php?paperID=<?php echo $paperid ?>","notice","width=350,height=150,scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    notice.moveTo(screen.width/2-175,screen.height/2-75);
    if (window.focus) {
      notice.focus();
    }
  }
</script>

<div id="left-sidebar" class="sidebar">
<form name="myform">

<div class="submenuheading" id="papertasks"><?php echo $string['examtasks'] ?></div>

<div class="menuitem"><a href="../paper/details.php?paperID=<?php echo $_GET['paperID']; ?>"><img class="sidebar_icon" src="../artwork/shortcut_16.png" alt="" /><?php echo $string['jumptopaper'] ?></a></div>
<div class="menuitem"><a href="#" onclick="editProperties(); return false;"><img class="sidebar_icon" src="../artwork/properties_icon.gif" alt="" /></td><td class="menuitem"><?php echo $string['editproperties'] ?></a></div>
<div class="menuitem"><a href="#" onclick="convtFormative(); return false;"><img class="sidebar_icon" src="../artwork/formative_16.gif" alt="" /></td><td class="menuitem"><?php echo $string['converttoformative'] ?></a></div>
<div class="menuitem"><a href="mailto:<?php echo $email . '?subject=Scheduling of ' . $paper_title . '&body=Dear ' . $title . ' ' . $surname . ','; ?>"><img class="sidebar_icon" src="../artwork/small_email.png" alt="" /><?php echo $string['Email'] . ' ' . $title . ' ' . $surname; ?></a></div>

<input type="hidden" id="divID" name="divID" value="" />
</form>
</div>

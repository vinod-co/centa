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
<div id="left-sidebar" class="sidebar">
<form name="myform">
<div id="menu1a">
	<div class="menuitem"><a href="add_ref_material.php?module=<?php echo $_GET['module']; ?>"><img class="sidebar_icon" src="../artwork/ref_16.png" alt="" /><?php echo $string['creatematerial'] ?></a></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/edit_grey.png" alt="" /><?php echo $string['editmaterial'] ?></div>
	<div class="grey menuitem"><img class="sidebar_icon" src="../artwork/red_cross_grey.png" alt="" /><?php echo $string['deletematerial'] ?></div>
</div>

<div style="display:none" id="menu1b">
	<div class="menuitem"><a href="add_ref_material.php"><img class="sidebar_icon" src="../artwork/ref_16.png" alt="" /><?php echo $string['creatematerial'] ?></a></div>
	<div class="menuitem"><a href="#" onclick="edit(); return false;"><img class="sidebar_icon" src="../artwork/edit.png" alt="" /><?php echo $string['editmaterial'] ?></a></div>
	<div class="menuitem"><a href="#" onclick="deleteReference(); return false;"><img class="sidebar_icon" src="../artwork/red_cross.png" alt="" /><?php echo $string['deletematerial'] ?></a></div>
</div>

<input type="hidden" id="lineID" name="lineID" value="" />
</form>
</div>

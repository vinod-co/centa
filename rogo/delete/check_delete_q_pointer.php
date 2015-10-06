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
* Confirm that it is OK to proceed deleting a question from a paper.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/staff_auth.inc';
require '../include/errors.inc';
require_once '../classes/paperproperties.class.php';

$questionID = check_var('questionID', 'GET', true, false, true);
$pID				=	check_var('pID', 'GET', true, false, true);
$paperID 		= check_var('paperID', 'GET', true, false, true);

$properties = PaperProperties::get_paper_properties_by_id($paperID, $mysqli, $string);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title><?php echo $string['confirmdelete'] ?></title>

  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/check_delete.css" />
</head>

<body>


<?php
  if ($properties->get_summative_lock()) {
		echo "<p>" . $string['msg2'] . "</p>\n";
	} else {
?>
<p><?php echo $string['msg'] ?></p>

<div class="button_bar">
<form action="do_delete_q_pointer.php" method="post">
<input type="hidden" name="module" value="<?php echo $_GET['module'] ?>" />
<input type="hidden" name="folder" value="<?php echo $_GET['folder'] ?>" />
<input type="hidden" name="scrOfY" value="<?php echo $_GET['scrOfY'] ?>" />
<input type="hidden" name="questionID" value="<?php echo $questionID ?>" />
<input type="hidden" name="pID" value="<?php echo $pID ?>" />
<input type="hidden" name="paperID" value="<?php echo $paperID ?>" />

<?php
  if (substr_count($_GET['pID'], ',')  > 1) {
    echo '<input class="delete" type="submit" name="submit" value="' . $string['deletes'] . '" />';
  } else {
    echo '<input class="delete" type="submit" name="submit" value="' . $string['delete'] . '" />';
  }

}
?>
<input class="cancel" type="button" name="cancel" value="<?php echo $string['cancel'] ?>" onclick="javascript:window.close();" />
</form>
</div>

</body>
</html>
<?php
	$mysqli->close();
?>
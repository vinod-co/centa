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

require '../include/staff_auth.inc';
require_once '../classes/paperutils.class.php';
require_once '../classes/moduleutils.class.php';
require_once '../include/errors.inc';

$property_id = check_var('property_id', 'POST', true, false, true);

function modulo($n,$b) {
  return $n-$b*floor($n/$b);
}

$session = $_POST['session'];

if (!$configObject->get('cfg_summative_mgmt') or $_POST['paper_type'] != 'summative') {
  if ((modulo($_POST['fyear'],4) == 0 and modulo($_POST['fyear'],100) != 0) or modulo($_POST['fyear'],400) == 0) {
    $leap = true;
  } else {
    $leap = false;
  }
  if ($leap == true and $_POST['fmonth'] == '02' and ($_POST['fday'] == '30' or $_POST['fday'] == '31')) $_POST['fday'] = '29';
  if ($leap == false and $_POST['fmonth'] == '02' and ($_POST['fday'] == '29' or $_POST['fday'] == '30' or $_POST['fday'] == '31')) $_POST['fday'] = '28';
  if (($_POST['fmonth'] == '04' or $_POST['fmonth'] == '06' or $_POST['fmonth'] == '09' or $_POST['fmonth'] == '11') and $_POST['fday'] == '31') $_POST['fday'] = '30';

  $UK_time = new DateTimeZone("Europe/London");
  $target_timezone = new DateTimeZone($_POST['timezone']);

  $start_date = new dateTime($_POST['fyear'] . $_POST['fmonth'] . $_POST['fday'] . $_POST['ftime'], $target_timezone);
  $start_date->setTimezone($UK_time);

  if ((modulo($_POST['tyear'],4) == 0 and modulo($_POST['tyear'],100) != 0) or modulo($_POST['tyear'],400) == 0) {
    $leap = true;
  } else {
    $leap = false;
  }
  if ($leap == true and $_POST['tmonth'] == '02' and ($_POST['tday'] == '30' or $_POST['tday'] == '31')) $_POST['tday'] = '29';
  if ($leap == false and $_POST['tmonth'] == '02' and ($_POST['tday'] == '29' or $_POST['tday'] == '30' or $_POST['tday'] == '31')) $_POST['tday'] = '28';
  if (($_POST['tmonth'] == '04' or $_POST['tmonth'] == '06' or $_POST['tmonth'] == '09' or $_POST['tmonth'] == '11') and $_POST['tday'] == '31') $_POST['tday'] = '30';

  $end_date = new dateTime($_POST['tyear'] . $_POST['tmonth'] . $_POST['tday'] . $_POST['ttime'], $target_timezone);
  $end_date->setTimezone($UK_time);

  if ($_POST['timezone'] < 0) {
    $start_date->modify("+" . abs($_POST['timezone']) . " hour");
    $end_date->modify("+" . abs($_POST['timezone']) . " hour");
  } elseif ($_POST['timezone'] > 0) {
    $start_date->modify("-" . $_POST['timezone'] . " hour");
    $end_date->modify("-" . $_POST['timezone'] . " hour");
  }

  $tmp_start_date = $start_date->format("YmdHis");
  $tmp_end_date = $end_date->format("YmdHis");
  $timezone = $_POST['timezone'];
}

// Process the posted modules
$modules = array();
$first = true;
for ($i=0; $i<$_POST['module_no']; $i++) {
  if (isset($_POST['mod' . $i])) {
    $module_code = module_utils::get_moduleid_from_id($_POST['mod' . $i], $mysqli);
    if ($first == true) {
      $first_module = $_POST['mod' . $i];
      $first = false;
    }
    $modules[$_POST['mod' . $i]] = $module_code;
  }
}
//add the modules to the paper
Paper_utils::add_modules($modules, $property_id, $mysqli);

$stmt = $mysqli->prepare("SELECT UNIX_TIMESTAMP(created), paper_ownerID FROM properties WHERE property_id = ?");
$stmt->bind_param('i', $property_id);
$stmt->execute();
$stmt->bind_result($created, $paper_ownerID);
$stmt->fetch();
$stmt->close();

$hash = $property_id . $created . $paper_ownerID;   // Generate the encrypted name of the paper.

if ($configObject->get('cfg_summative_mgmt') and $_POST['paper_type'] == 'summative') {
  $duration = 0;
	if (isset($_POST['duration_hours'])) {
		$duration += ($_POST['duration_hours'] * 60);
	}
	if (isset($_POST['duration_mins'])) {
		$duration += $_POST['duration_mins'];
	}

  $result = $mysqli->prepare("UPDATE properties SET deleted = NULL, crypt_name = ?, calendar_year = ?, exam_duration = ? WHERE property_id = ? LIMIT 1");
  $result->bind_param('ssii', $hash, $session, $duration, $property_id);
  $result->execute();
  $result->close();

  if (isset($_POST['barriers_needed'])) {
    $barriers_needed = 1;
  } else {
    $barriers_needed = 0;
  }

  // Do a delete just in case of double-submission of the form.
  $result = $mysqli->prepare("DELETE FROM scheduling WHERE paperID = ?");
  $result->bind_param('i', $property_id);
  $result->execute();
  $result->close();

  $result = $mysqli->prepare("INSERT INTO scheduling VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
  $result->bind_param('isissis', $property_id, $_POST['period'], $barriers_needed, $_POST['cohort_size'], $_POST['notes'], $_POST['sittings'], $_POST['campus']);
  $result->execute();
  $result->close();
} else {
  if (isset($session) and $session != '') {
    $result = $mysqli->prepare("UPDATE properties SET start_date = ?, end_date = ?, timezone = ?, deleted = NULL, crypt_name = ?, calendar_year = ? WHERE property_id = ? LIMIT 1");
    $result->bind_param('sssssi', $tmp_start_date, $tmp_end_date, $timezone, $hash, $session, $property_id);
  } else {
    $result = $mysqli->prepare("UPDATE properties SET start_date = ?, end_date = ?, timezone = ?, deleted = NULL, crypt_name = ? WHERE property_id = ? LIMIT 1");
    $result->bind_param('ssssi', $tmp_start_date, $tmp_end_date, $timezone, $hash, $property_id);
  }
  $result->execute();
  $result->close();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>New Paper</title>
  <script>
    function jumpToPaper() {
      <?php
        if ($_POST['folder'] != '') {
          echo 'window.opener.location = "details.php?paperID=' . $_POST['property_id'] . '&folder=' . $_POST['folder'] . '";';
        } else {
          echo 'window.opener.location = "details.php?paperID=' . $_POST['property_id'] . '&module=' . $first_module . '";';
        }
      ?>
      window.close();
    }
  </script>
</head>
<body onload="jumpToPaper()">
</body>
</html>

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
* The results screen of a search for a user(s).
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/

require '../include/staff_auth.inc';
require_once '../include/demo_replace.inc';
require_once '../classes/paperproperties.class.php';

function get_special_needs($db) {
  $needs_array = array();
  $result = $db->prepare("SELECT userID FROM special_needs");
  $result->execute();
  $result->bind_result($tmp_userID);
  while ($result->fetch()) {
    $needs_array[$tmp_userID] = '1';
  }
  $result->close();
  
  return $needs_array;
}

if ($userObject->has_role('Demo')) {
  $demo = true;
} else {
  $demo = false;
}
$sortby = 'surname';
$ordering = 'asc';
$moduleID = '%';
$calendar_year =  (isset($_GET['calendar_year']) and $_GET['calendar_year'] != '') ? $_GET['calendar_year'] : '%';

if ($calendar_year == '%') {
  $calendar_year_sql = '';
} else {
  $calendar_year_sql = " AND calendar_year = '$calendar_year'";
}

$needs_array = get_special_needs($mysqli);

// We should only display the first 10,000 rows to avoid browser issues.
$limit = 10000;

if (isset($_GET['submit'])) {
  $username_sql = '';
  $title_sql = '';
  $surname_sql = '';
  $initials_sql = '';
  $student_id_sql = '';
  $title = '';

  if (isset($_GET['module']) and $_GET['module'] != '') $moduleID = $_GET['module'];

  if (isset($_GET['search_surname']) and $_GET['search_surname'] != '') {
    $tmp_surname = str_replace("*", "%", trim($_GET['search_surname']));

    $tmp_titles = explode(',', $string['title_types']);
    foreach ($tmp_titles as $tmp_title) {
      if (substr_count(strtolower($tmp_surname), strtolower($tmp_title . ' ')) > 0) $title_sql = " AND title='$tmp_title'";
      $tmp_surname = preg_replace("/(" . $tmp_title . " )/i","",$tmp_surname);
    }

    $sections = preg_split('[,.]',$tmp_surname);
    if (count($sections) > 1) {    // Search for initials.
      if (strlen($sections[0]) < strlen($sections[1])) {
        $initials_sql = " AND initials LIKE '" . trim($sections[0]) . "%'";
        $tmp_surname = trim($sections[1]);
      } else {
        $initials_sql = " AND initials LIKE '" . trim($sections[1]) . "%'";
        $tmp_surname = trim($sections[0]);
      }
    } else {
      $initials_sql = '';
    }
    $tmp_surname = str_replace('*','%',$tmp_surname);
    $surname_sql = " AND surname LIKE '$tmp_surname'";
  }
  if ($_GET['search_username'] != '') {
    $tmp_username = str_replace('*', '%', trim($_GET['search_username']));
    $username_sql = " AND users.username LIKE '$tmp_username'";
  }

  if ($_GET['student_id'] != '') {
    $student_id_sql = " AND student_id = '" . trim($_GET['student_id']) . "'";
  }

  $roles_sql = '';
  if ((isset($_GET['students']) and $_GET['students'] != '') or (isset($_GET['student_id']) and $_GET['student_id'] != '') ) $roles_sql .= " OR roles LIKE '%Student'";
  if (isset($_GET['staff']) and $_GET['staff'] != '') $roles_sql .= " OR roles LIKE '%Staff%'";
  if (isset($_GET['adminstaff']) and $_GET['adminstaff'] != '') $roles_sql .= " OR roles LIKE '%,Admin%'";
  if (isset($_GET['sysadminstaff']) and $_GET['sysadminstaff'] != '') $roles_sql .= " OR roles LIKE '%,SysAdmin%'";
  if (isset($_GET['inactive']) and $_GET['inactive'] != '') $roles_sql .= " OR roles LIKE '%inactive%'";
  if (isset($_GET['externals']) and $_GET['externals'] != '') $roles_sql .= " OR (roles = 'External Examiner' AND grade != 'left')";
  if (isset($_GET['invigilators']) and $_GET['invigilators'] != '') $roles_sql .= " OR roles = 'Invigilator'";
  if (isset($_GET['graduates']) and $_GET['graduates'] != '') $roles_sql .= " OR roles = 'Graduate'";
  if (isset($_GET['leavers']) and $_GET['leavers'] != '') $roles_sql .= " OR roles = 'left'";
  if (isset($_GET['suspended']) and $_GET['suspended'] != '') $roles_sql .= " OR roles = 'suspended'";
  if ($roles_sql != '') $roles_sql = '(' . substr($roles_sql,4) . ')';
  if (isset($_GET['leavers']) and $_GET['leavers'] == '' and isset($_GET['staff']) and  $_GET['staff'] != '') $roles_sql .= " AND grade != 'left'";

	$user_no = 0;
  if ($roles_sql != '') {
    if ((isset($_GET['staff']) and $_GET['staff'] != '') or (isset($_GET['inactive']) and $_GET['inactive'] != '') or (isset($_GET['sysadminstaff']) and $_GET['sysadminstaff'] != '') or (isset($_GET['adminstaff']) and $_GET['adminstaff'] != '') or (isset($_GET['invigilators']) and $_GET['invigilators'] != '')) {
      if ($_GET['module'] != '') {
        $query_string = "(SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
        FROM (users, modules_student, modules)
        LEFT JOIN sid ON users.id = sid.userID
        WHERE modules_student.idMod = modules.id
        AND users.id = modules_student.userID
        AND modules_student.idMod = '" . $_GET['module'] . "'
        AND $roles_sql$surname_sql$title_sql$username_sql$initials_sql$calendar_year_sql
        AND user_deleted IS NULL)
        UNION
        (SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
        FROM (users, modules_staff, modules)
        LEFT JOIN sid ON users.id = sid.userID
        WHERE modules_staff.idMod = modules.id
        AND users.id = modules_staff.memberID
        AND modules_staff.idMod = '" . $_GET['module'] . "'
        AND $roles_sql$surname_sql$title_sql$username_sql$initials_sql
        AND user_deleted IS NULL LIMIT $limit)";
      } else {
        $query_string = "SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
          FROM users
          LEFT JOIN sid ON users.id = sid.userID
          WHERE $roles_sql$surname_sql$title_sql$username_sql$initials_sql
          AND user_deleted IS NULL LIMIT $limit";
      }
    } elseif (isset($_GET['externals']) and $_GET['externals'] != '') {
      $query_string = "SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
        FROM users
        LEFT JOIN sid ON users.id = sid.userID
        WHERE $roles_sql$surname_sql$title_sql$username_sql$initials_sql
        AND user_deleted IS NULL LIMIT $limit";
    } else {
      // Student search
      if ($moduleID == '%') {
        $query_string = "SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
          FROM users
          LEFT JOIN sid ON users.id = sid.userID
          WHERE $roles_sql$surname_sql$title_sql$username_sql$student_id_sql$initials_sql
          AND user_deleted IS NULL LIMIT $limit";
      } else {
        $roles_sql = 'AND ' . $roles_sql;
        if ($moduleID == '%') {
          $module_sql = '';
        } else {
          $module_sql = " AND idMod LIKE '{$moduleID}'";
        }
        $query_string = "SELECT DISTINCT users.id, roles, student_id, surname, initials, first_names, title, users.username, grade, yearofstudy, email
          FROM (users, modules_student)
          LEFT JOIN sid ON users.id = sid.userID
          WHERE users.id = modules_student.userID $module_sql$calendar_year_sql$roles_sql$surname_sql$title_sql$username_sql$student_id_sql$initials_sql
          AND user_deleted IS NULL LIMIT $limit";
      }
    }

    $user_data = $mysqli->prepare($query_string);
    $user_data->execute();
    $user_data->bind_result($tmp_id, $tmp_roles, $tmp_student_id, $tmp_surname, $tmp_initials, $tmp_first_names, $tmp_title, $tmp_username, $tmp_grade, $tmp_yearofstudy, $tmp_email);
    $user_data->store_result();
    $user_no = number_format($user_data->num_rows);
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  <title>Rog&#333;: <?php echo $string['usermanagement'] . ' ' . $configObject->get('cfg_install_type'); ?></title>
  <link rel="stylesheet" type="text/css" href="../css/body.css" />
  <link rel="stylesheet" type="text/css" href="../css/header.css" />
  <link rel="stylesheet" type="text/css" href="../css/submenu.css" />
  <link rel="stylesheet" type="text/css" href="../css/list.css" />
  <link rel="stylesheet" type="text/css" href="../css/warnings.css" />
  <style type="text/css">
    a {color:black}
    .coltitle {cursor:hand; background-color:#F1F5FB; color:black}
    #usertable td {padding-left:6px}
    .fn {color:#A5A5A5}
    .uline {line-height: 150%}
    .uline:hover {background-color:#FFE7A2}
    .uline.highlight {background-color:#FFBD69}
    td {padding-left: 0 !important}
    .l {line-height: 160%}
  </style>

  <script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery_tablesorter/jquery.tablesorter.js"></script>
  <script type="text/javascript" src="../js/staff_help.js"></script>
  <script type="text/javascript" src="../js/toprightmenu.js"></script>
  <script>
    function addUserID(ID, clearall) {
      if (clearall) {
        $('#userID').val(',' + ID);
      } else {
        cur_value = $('#userID').val() + ',' + ID;
        $('#userID').val(cur_value);
      }
    }

    function subUserID(ID) {
      var tmpuserID = ',' + ID;
      new_value = $('#userID').val().replace(tmpuserID, '');
      $('#userID').val(new_value);
    }

    function clearAll() {
      $('.highlight').removeClass('highlight');
    }

    function selUser(userID, lineID, menuID, roles, evt) {
      $('#menu2a').hide();
      $('#menu' + menuID).show();

      if (evt.ctrlKey == false && evt.metaKey == false) {
        clearAll();
        $('#' + lineID).addClass('highlight');
        addUserID(userID, true);
      } else {
        if ($('#' + lineID).hasClass('highlight')) {
          $('#' + lineID).removeClass('highlight');
          subUserID(userID);
        } else {
          $('#' + lineID).addClass('highlight');
          addUserID(userID, false);
        }
      }
      $('#roles').val(roles);
      checkRoles();
      
      evt.stopPropagation();
    }

    function userOff() {
      $('#menu2a').show();
      $('#menu2b').hide();
      $('#menu2c').hide();

      clearAll();
    }

    function profile(userID) {
      document.location.href='details.php?search_surname=<?php if (isset($_GET['search_surname'])) echo $_GET['search_surname'] ?>&search_username=<?php if (isset($_GET['search_username'])) echo $_GET['search_username'] ?>&student_id=<?php if (isset($_GET['student_id'])) echo $_GET['student_id'] ?>&moduleID=<?php if (isset($_GET['team'])) echo $_GET['team']; if (isset($_GET['module'])) echo '&module=' . $_GET['module'] ?>&calendar_year=<?php if (isset($_GET['calendar_year'])) echo $_GET['calendar_year'] ?>&students=<?php if (isset($_GET['students'])) echo $_GET['students']; ?>&submit=Search&userID=' + userID + '&email=<?php if (isset($_GET['email'])) echo $_GET['email']; ?>&tmp_surname=<?php if (isset($_GET['tmp_surname'])) echo $_GET['tmp_surname']; ?>&tmp_courseID=<?php if (isset($_GET['tmp_courseID'])) echo $_GET['tmp_courseID']; ?>&tmp_yearID=<?php if (isset($_GET['tmp_yearID'])) echo $_GET['tmp_yearID']; ?>';
    }
    
    $(function () {
      if ($("#maindata").find("tr").size() > 1) {
        $("#maindata").tablesorter({ 
          // sort on the third column, order asc 
          sortList: [[3,0]] 
        });
      }

      $(document).click(function() {
        $('#menudiv').hide();
      });
    });
  </script>
</head>

<?php
  require '../include/toprightmenu.inc';

	echo draw_toprightmenu(92);
	
  if (isset($_GET['submit']) or isset($_GET['paperID']) or isset($_GET['moduleID'])) {
    echo "<body>\n";

    include '../include/user_search_options.inc';

    echo "<div id=\"content\" class=\"content\">\n";
  } else {
    echo "<body>\n";

    include '../include/user_search_options.inc';

    echo "<div id=\"content\" class=\"content\">\n";
    echo "<div class=\"head_title\">\n";
    echo "<div><img src=\"../artwork/toprightmenu.gif\" id=\"toprightmenu_icon\" /></div>";
    echo "<div class=\"breadcrumb\"><a href=\"../index.php\">" . $string['home'] . "</a>";
    if (isset($_REQUEST['module'])) {
      echo '<img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../module/index.php?module=' . $_REQUEST['module'] . '">' . module_utils::get_moduleid_from_id($_REQUEST['module'], $mysqli) . '</a>';
    }
    echo "</div><div class=\"page_title\">" . $string['usersearch'] . "</div>";
    echo "</div>\n</div>\n</body></html>\n";
    exit();     // There is no search submit so just exit.
  }
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?sortby=<?php echo $sortby; ?>&order=<?php echo $ordering; ?>">

<div class="head_title">
<div style="float:right; vertical-align:top"><img src="../artwork/toprightmenu.gif" id="toprightmenu_icon" /></div>
<?php
echo "<div class=\"breadcrumb\"><a href=\"../index.php\">" . $string['home'] . "</a>";
if (isset($_GET['module']) and $_GET['module'] != '') {
  echo '<img src="../artwork/breadcrumb_arrow.png" class="breadcrumb_arrow" alt="-" /><a href="../module/index.php?module=' . $_GET['module'] . '">' . module_utils::get_moduleid_from_id($_GET['module'], $mysqli) . '</a>';
}
echo "</div><div class=\"page_title\">" . $string['usersearch'] . " ($user_no): <span style=\"font-weight: normal\">";
if (isset($_GET['paperID'])) {
  echo implode(', ', array_values($paper_modules)) . ' (' . $paper_calendar_year . ')';
} elseif (isset($_GET['search_surname']) and $_GET['search_surname'] != '') {
  echo "'" . $_GET['search_surname'] . "'";
} elseif (isset($_GET['module']) and $_GET['module'] != '%') {
  echo module_utils::get_moduleid_from_id($_GET['module'], $mysqli);
  if (isset($_GET['calendar_year']) and $_GET['calendar_year'] != '' and isset($_GET['students']) and $_GET['students'] != '') {
    echo ' (' . $_GET['calendar_year'] . ')';
  }
} elseif (isset($_GET['search_username']) and $_GET['search_username'] != '') {
  echo $_GET['search_username'];
} elseif (isset($_GET['student_id']) and $_GET['student_id'] != '') {
  echo $_GET['student_id'];
} elseif (isset($_GET['calendar_year']) and $_GET['calendar_year'] != '%') {
  echo $_GET['calendar_year'];
}
echo "</span></div>\n";
echo "</div>\n";

if (isset($_GET['search_surname'])) {
  $tmp_surname = $_GET['search_surname'];
} else {
  $tmp_surname = '';
}

if (isset($_GET['search_username'])) {
  $tmp_username = $_GET['search_username'];
} else {
  $tmp_username = '';
}

if (isset($_GET['student_id'])) {
  $tmp_student_id = $_GET['student_id'];
} else {
  $tmp_student_id = '';
}

if ($roles_sql == '') {
  echo "<div>" . $notice->info_strip($string['msg1'], 100) . "</div>";
  exit();
}

if ($user_data->num_rows == $limit) {
  echo " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\"><tr><td class=\"redwarn\" style=\"width:40px; line-height:0; padding-left:0\"><img src=\"../artwork/exclamation_red_bg.png\" width=\"32\" height=\"32\" alt=\""
    . $string['warning'] . "\" /></td>" . "<td class=\"redwarn\">" . $string['largeresult'] . "</td></tr></table>";
}

$table_order = array('#1', '#2', $string['title'], 'Surname', 'First Names', $string['username'], $string['studentid'], $string['year'], $string['course']);
echo "<table id=\"maindata\" class=\"header tablesorter\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width:100%\">\n";
echo "<thead>\n";
echo "<tr>\n";
foreach ($table_order as $display) {
  if ($display{0} == '#') {
    echo "<th>&nbsp;</th>";
  } else {
    echo "<th class=\"col\">$display</th>\n";
  }    
}
?>
</tr>
</thead>

<tbody>
<?php

if ($user_data->num_rows == 0) {
  echo "</table>" . $notice->info_strip($string['msg2'], 100) . "</div>\n</body>\n</html>\n";
  exit();
}

$x = 0;
while ($user_data->fetch()) {
  if ($userObject->has_role('SysAdmin')) {
    echo "<tr class=\"l\" id=\"$x\" onclick=\"selUser('$tmp_id',$x,'2c','" . $tmp_roles . "',event); return false;\" ondblclick=\"profile('$tmp_id'); return false;\">";
  } else {
    echo "<tr class=\"l\" id=\"$x\" onclick=\"selUser('$tmp_id',$x,'2b','" . $tmp_roles . "',event); return false;\" ondblclick=\"profile('$tmp_id'); return false;\">";
  }
  if (file_exists($cfg_web_root . 'users/photos/' . $tmp_username . '.jpg')) {
    echo '<td><img src="../artwork/photo.png" width="16" height="16" alt="Photo" /></td>';
  } else {
    echo '<td></td>';
  }
  if (array_key_exists($tmp_id, $needs_array)) {
    echo '<td><img src="../artwork/accessibility_16.png" width="16" height="16" /></td>';
  } else {
    echo '<td></td>';
  }

  if ($tmp_title != null) {
    $lowertitle = mb_strtolower($tmp_title);
    if (array_key_exists($lowertitle, $string)) {
      echo '<td>' . $string[$lowertitle] . '</td>';
    } else {
      echo '<td></td>';
    }
  } else {
    echo '<td></td>';
  }
  
  if ($tmp_first_names == '') $tmp_first_names = ' ';
  if ($tmp_surname == '') $tmp_surname = ' ';
  echo '<td>' . demo_replace($tmp_surname, $demo, true, $tmp_surname{0}) . '</td>';
  echo '<td>' . demo_replace($tmp_first_names, $demo, true, $tmp_first_names{0}) . '</td>';
  echo '<td>' . demo_replace($tmp_username, $demo, false) . '</td>';
      
  if (strpos($tmp_roles, 'Student') !== false) {
    if ($tmp_student_id == NULL) {
      echo '<td class="fn">' . $string['unknown'] . '</td>';
    } else {
      echo '<td>' . demo_replace_number($tmp_student_id, $demo) . '</td>';
    }
  } elseif (strpos($tmp_roles, 'Staff') !== false) {
    echo "<td>Staff</td>";
  } else {
    echo "<td class=\"fn\">" . $string['na'] . "</td>";
  }
  echo "<td>$tmp_yearofstudy</td>";
  echo "<td>$tmp_grade</td></tr>\n";
  
  $x++;
}

$user_data->close();
$mysqli->close();
?>
</tbody>
</table>
</div>

</body>
</html>
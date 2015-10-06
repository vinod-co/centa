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
 * This script takes the papers and compares the count of records in the appropriate log table by distinct and non distinct
 *
 * @author Simon Atack
 * @version 1.0
 * @copyright Copyright (c) 2014 The University of Nottingham
 * @package Rogō
 */

$typelist = array(3, 1, 2, 0);
$typelist = array( 0);
$cfg_web_root='../';
require_once '../include/custom_error_handler.inc';
require_once '../classes/configobject.class.php';
require_once '../classes/usernotices.class.php';
require_once '../classes/userobject.class.php';
require_once '../classes/dbutils.class.php';
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);
error_reporting(E_ALL);
@ob_flush();
@ob_end_flush();
@ob_implicit_flush(1);
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('implicit_flush', 1);

set_time_limit(0);
$configObject = Config::get_instance();
$notice = null;
$mysqli = DBUtils::get_mysqli_link($configObject->get('cfg_db_host'), $configObject->get('cfg_db_staff_user'), $configObject->get('cfg_db_staff_passwd'), $configObject->get('cfg_db_database'), $configObject->get('cfg_db_charset'), $notice, $configObject->get('dbclass'), $configObject->get('cfg_db_port'));

error_reporting(E_ALL);
foreach ($typelist as $type) {
  $paper_type = $type;


  $sql = "select property_id,date_format(start_date,'%Y%m%d%H%i%S') as start_date, date_format(end_date,'%Y%m%d%H%i%S') as end_date, paper_title from properties where property_id=4479 and paper_type='$type'";

  $result = $mysqli->prepare($sql);
  if ($mysqli->error) {
    try {
      throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
    } catch (Exception $e) {
      echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
      echo nl2br($e->getTraceAsString());
      exit();
    }
  }
  $result->execute();
  $result->store_result();
  $result->bind_result($propertyid, $start_date, $end_date, $papertitle);

  $records = $result->num_rows;


  echo <<<HTML
	<html>
<body>
<h1>Initial selection: $sql</h1><br />
<h2>$records Rows Found</h2>
<table>
<tr><th>PaperID</th><th>Paper Name</th><th>Count</th><th>Distinct Count</th><th>Error</th><th></th><th></th></tr>
HTML;

  $roles_sql = " AND (users.roles='Student' OR users.roles='graduate')";
  $errorcount = 0;


  while ($result->fetch()) {


    if ($paper_type == '0') {

      $cfg_long_date_time = $configObject->get('cfg_long_date_time');

      $sql = '(SELECT log_metadata.userID, log_metadata.started,log0.screen,log0.q_id
                          , 0 AS paper_type
                          , grade
                          , roles
                          , screen
                          , duration
                          , started AS order_started
                          , user_answer
                          , DATE_FORMAT(started, "' . $cfg_long_date_time . '") AS display_started
                          , year
                          , title
                          , surname
                          , initials
                          , first_names
                          , gender
                          , ipaddress
                          , lab_name
                          , username
                          , users.id
                          , student_id
                          , user_answer
                          , q_type
                          , log_metadata.userID
                          , mark
                          , status
                          , attempt
              FROM
                  log0, log_metadata, questions, users
              LEFT JOIN
                  sid
              ON
                  users.id = sid.userID
              WHERE
                  log_metadata.userID = users.id
              AND
                  log0.metadataID = log_metadata.id
              AND
                  log0.q_id    = questions.q_id
              AND
                  paperID = ?

              ' . $roles_sql . '
              AND
                  started >= ?
              AND
                  started <= ? )

                  ORDER BY
                      log0.q_id, log_metadata.userID
                    , screen, order_started
                    ';

      $log_query = $mysqli->prepare($sql);
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    } elseif ($paper_type == '2') {
      $log_query = $mysqli->prepare("SELECT  log2.q_id, 2 AS paper_type, grade, roles, screen, duration, started, user_answer, DATE_FORMAT(started, '{$configObject->get('cfg_long_date_time')}') AS display_started, year, title, surname, initials, first_names, gender, ipaddress, lab_name, username, users.id, student_id, user_answer, q_type, log_metadata.userID, mark, status, attempt FROM (log2, log_metadata, questions, users ) LEFT JOIN sid ON users.id = sid.userID WHERE log_metadata.userID = users.id AND log2.metadataID = log_metadata.id AND log2.q_id = questions.q_id AND paperID = ? $roles_sql AND DATE_ADD(started, INTERVAL 2 MINUTE) >= ? AND started <= ? ORDER BY userID, started, screen");
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    } elseif ($paper_type == '5') {
      $log_query = $mysqli->prepare("SELECT  log5.q_id, 5 AS paper_type, grade, roles, 1 AS screen, 0 AS duration, started, NULL AS user_answer, DATE_FORMAT(started, '{$configObject->get('cfg_long_date_time')}') AS display_started, log_metadata.year, title, surname, initials, first_names, gender, NULL AS ipaddress, lab_name, username, users.id, student_id, NULL AS user_answer, q_type, userID, mark, status, 1 AS attempt FROM (log5, log_metadata, questions, users ) LEFT JOIN sid ON users.id=sid.userID WHERE log_metadata.userID = users.id AND log5.metadataID = log_metadata.id AND log5.q_id=questions.q_id AND paperID = ? $roles_sql AND DATE_ADD(started, INTERVAL 2 MINUTE) >= ? AND started <= ? ORDER BY userID, started, screen");
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    } elseif ($paper_type == '4') {
      $log_query = $mysqli->prepare("SELECT  log$paper_type.q_id, $paper_type AS paper_type, grade, roles, 2 as screen, 2 as duration, log_metadata.started, 2 as user_answer, DATE_FORMAT(started, '{$configObject->get('cfg_long_date_time')}') AS display_started, log_metadata.year, title, surname, initials, first_names, gender, ipaddress, lab_name, username, users.id, student_id, 2 as user_answer, q_type, log_metadata.userID, mark, status, attempt FROM (log$paper_type, log_metadata, questions, users ) LEFT JOIN sid ON users.id=sid.userID WHERE log_metadata.userID = users.id AND log$paper_type.metadataID = log_metadata.id AND log$paper_type.q_id = questions.q_id AND paperID = ? AND users.id=log_metadata.userID $roles_sql AND started>=? AND started<=? ORDER BY userID, started, screen");
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    } else {
      $log_query = $mysqli->prepare("SELECT  log$paper_type.q_id, $paper_type AS paper_type, grade, roles, screen, duration, started, user_answer, DATE_FORMAT(started, '{$configObject->get('cfg_long_date_time')}') AS display_started, log_metadata.year, title, surname, initials, first_names, gender, ipaddress, lab_name, username, users.id, student_id, user_answer, q_type, log_metadata.userID, mark, status, attempt FROM (log$paper_type, log_metadata, questions, users ) LEFT JOIN sid ON users.id=sid.userID WHERE log_metadata.userID = users.id AND log$paper_type.metadataID = log_metadata.id AND log$paper_type.q_id = questions.q_id AND paperID = ? AND users.id=log_metadata.userID $roles_sql AND started>=? AND started<=? ORDER BY userID, started, screen");
      if ($mysqli->error) {
        try {
          throw new Exception("MySQL error $mysqli->error <br> Query:<br> ", $mysqli->errno);
        } catch (Exception $e) {
          echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br />";
          echo nl2br($e->getTraceAsString());
          exit();
        }
      }
      $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    }

    // $log_query->bind_param('iss', $propertyid, $start_date, $end_date);
    $log_query->execute();
    $log_query->bind_result($uid,$ustd,$uscn,$q_id, $paper_type, $grade, $tmp_roles, $screen, $duration, $started, $user_answer, $display_started, $year, $title, $surname, $initials, $first_names, $gender, $ipaddress, $lab_name, $username, $tmp_userID, $student_id, $user_answer, $q_type, $tmp_userID, $mark, $status, $attempt);


    $log_query->store_result();
    $NOTdistinctCNT = $log_query->num_rows;
    echo $NOTdistinctCNT ."<br>\n";
    $olduid='';
    $oldustd='';
    $olduscn='';
    $oldq_id='';
    $dataobj[0]=0;
    $dataobj[1]=0;
    $dataobj[2]=0;
    $dataobj[3]=0;
    $dataobj[4]=0;

    while($get1=$log_query->fetch()) {

      $cnt22=0;
      if($olduid==$uid ) {
        $a='*';
        $cnt22++;
      }
      else {$a='^';}
      if($oldustd==$ustd) {
        $b='*';$cnt22++;
      }      else {$b='^';}
      if($olduscn==$uscn ) {
        $c='*';$cnt22++;
      }       else {$c='^';}
      if( $oldq_id==$q_id) {
        $d='*';$cnt22++;
      }  else {$d='^';}
      $cols=array('black','green','yellow','orange','red');
      $lk=$cols[$cnt22];
      $dataobj[$cnt22]++;
      $dataobji[$cnt22][]="$q_id,$uid,$uscn,$ustd";
      if ($olduid == $uid and $oldustd == $ustd and $olduscn == $uscn and $oldq_id == $q_id) {
        print "DUPLICATE:$uid,$ustd,$uscn,$q_id<br>\n";
      } else {
     //   print "different:$d$a$c$b:::$q_id,$uid,$uscn,$ustd############<span style=\"background-color:$lk\">$cnt22</span><br>\n";
      }

      $olduid=$uid;
      $oldustd=$ustd;
      $olduscn=$uscn;
      $oldq_id=$q_id;

    }
var_dump($dataobj);
var_dump($dataobji[4]);

    $log_query->close();

   // $value = array($papertitle, $distinctCNT, $NOTdistinctCNT, $start_date, $end_date);


/*
    $same = true;
    if ($value[1] != $value[2]) {
      $same = false;

    }
    if ($same == false) {
      $extra = ' style="background-color:red" ';
      $error = 'ERROR';
      $errorcount++;
    } else {
      $extra = ' style="background-color:green" ';
      $error = '';
    }
    echo <<<HTML
	<tr><td $extra>$propertyid</td><td $extra>$value[0]</td><td>$value[1]</td><td>$value[2]</td><td $extra>$error</td><td>$value[3]</td><td>$value[4]</td></tr>

HTML;*/
    @ob_flush();
    @flush();

  }


  echo <<<HTML
		</table>
		<h3>There are a total of $errorcount Records that do not match for type $type.</h3>
		</bodydis></htmldis>
HTML;
  $errorsdata[$type] = $errorcount;
}

echo <<<HTML
<h1>TOTAL SUMMARY</h1>
HTML;
foreach ($errorsdata as $key => $value) {
  echo <<<HTML
TYPE: $key ::: $value <br />
HTML;

}
?>



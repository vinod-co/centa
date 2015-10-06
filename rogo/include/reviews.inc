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

require_once realpath(dirname(__FILE__) . '/../classes/networkutils.class.php');

function time_to_seconds($seconds) {
  $hr = intval(substr($seconds,8,2));
  $min = intval(substr($seconds,10,2));
  $sec = intval(substr($seconds,12,2));
	
  return ($hr * 3600) + ($min * 60) + $sec;
}

function create_metadataID($paperID, $reviewerID, $review_type, $db) {
  $ipaddress = NetworkUtils::get_client_address();

  $stmt = $db->prepare("INSERT INTO review_metadata VALUES(NULL, ?, ?, NOW(), NULL, ?, ?, NULL)");
  $stmt->bind_param('iiss', $reviewerID, $paperID, $review_type, $ipaddress);
  $stmt->execute();
  $reviewID = $db->insert_id;
  $stmt->close();

  return $reviewID;
}

function get_metadataID($paperID, $reviewerID, $review_type, $db) {
  $stmt = $db->prepare("SELECT MAX(id) FROM review_metadata WHERE paperID = ? AND reviewerID = ?");
  $stmt->bind_param('ii', $paperID, $reviewerID);
  $stmt->execute();
  $stmt->bind_result($reviewID);
  $stmt->store_result();
  if ($stmt->num_rows < 1) {
    $reviewID = create_metadataID($paperID, $reviewerID, $review_type, $db);
  } else {
    $stmt->fetch();
  }
  $stmt->close();
  
  return $reviewID;
}

function record_comments($paper_id, $screen_no, $dblink, $userid, $review_type) {
	
  $question_no = 0;
  $old_q_id = NULL;
  $submit_time = date("YmdHis", time());
  
  $metadataID = get_metadataID($paper_id, $userid, $review_type, $dblink);

  $stmt = $dblink->prepare("SELECT q_id, q_type FROM (papers, questions) WHERE paper = ? AND screen = ? AND papers.question = questions.q_id ORDER BY display_pos");
  $stmt->bind_param('ii', $paper_id, $screen_no);
  $stmt->execute();
  $stmt->bind_result($q_id, $q_type);
  $stmt->store_result();
  while ($stmt->fetch()) {
    if ($old_q_id != $q_id) {
      // Record external examiner comments.
      if ($q_type != 'info') {
        $question_no++;
        
        $result = $dblink->prepare("DELETE FROM review_comments WHERE metadataID = ? AND q_id = ?");
        $result->bind_param('ii', $metadataID, $q_id);
        $result->execute();  
        $result->close();

        $tmp_duration = time_to_seconds($submit_time) - time_to_seconds($_POST['page_start']);
        if ($tmp_duration < 0) $tmp_duration += 86400;
        $tmp_duration += $_POST['previous_duration'];
        $extcomments = $_POST["extcomments$question_no"];
        
        $result = $dblink->prepare("INSERT INTO review_comments VALUES (NULL, ?, ?, ?, 'Not actioned', '', $tmp_duration, ?, ?)");
        $result->bind_param('iisii', $q_id, $_POST["exttype$question_no"], $extcomments, $_POST['old_screen'], $metadataID);
        $result->execute();  
        $result->close();
      }
    }
    $old_q_id = $q_id;
  }                    // End of while loop.
  $stmt->close();
}

function record_general_comments($paper_comment, $paper_id, $userid, $finish, $review_type, $db) {
  $metadataID = get_metadataID($paper_id, $userid, $review_type, $db);

  if ($finish) {
    $stmt = $db->prepare("UPDATE review_metadata SET paper_comment = ? AND complete = NOW() WHERE id = ?");
    $stmt->bind_param('si', $paper_comment, $metadataID);
  } else {
    $stmt = $db->prepare("UPDATE review_metadata SET paper_comment = ? WHERE id = ?");
    $stmt->bind_param('si', $paper_comment, $metadataID);    
  }
  $stmt->execute();
  $stmt->close();
}

function get_paper_comments($id) {
  $stmt = $db->prepare("SELECT paper_comment FROM review_metadata WHERE id = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->bind_result($paper_comment);
  $stmt->fetch();
  $stmt->close();
  
  return $paper_comment;
}
?>
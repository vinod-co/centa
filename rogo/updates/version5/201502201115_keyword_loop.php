<?php

// Delete any keywords associate with keyword based questions - ROGO-649
if (!$updater_utils->has_updated('keyword_loop')) {

	$delete = $mysqli->prepare("DELETE keywords_question FROM keywords_question, questions WHERE q_type = 'keyword_based' AND keywords_question.q_id = questions.q_id");
	$delete->execute();
	$delete->close();
	
	$updater_utils->record_update('keyword_loop');
}

?>

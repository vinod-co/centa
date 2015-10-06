<?php

// Remove HTML colour formatting from the database (track_changes table).

  require $cfg_web_root . 'lang/' . $language . '/question/edit/index.php';

  $sql = "UPDATE track_changes SET part='" . $string['markscorrect'] . "' WHERE part='<span style=\"color: red; font-weight: bold\">" . $string['markscorrect'] . "</span>'";
	$update = $mysqli->prepare($sql);
	$update->execute();
	$update->close();

  $sql = "UPDATE track_changes SET part='" . $string['marksincorrect'] . "' WHERE part='<span style=\"color: red; font-weight: bold\">" . $string['marksincorrect'] . "</span>'";
	$update = $mysqli->prepare($sql);
	$update->execute();
	$update->close();

  $sql = "UPDATE track_changes SET part='" . $string['markspartial'] . "' WHERE part='<span style=\"color: red; font-weight: bold\">" . $string['markspartial'] . "</span>'";
	$update = $mysqli->prepare($sql);
	$update->execute();
	$update->close();
?>
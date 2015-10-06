<?php
	require '../include/sysadmin_auth.inc';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
<head>
<title>Calculation functions search</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
</head>

<body>
<?php
set_time_limit(0);


$sql = "select settings from questions where q_id=123507 or q_id=122995";
$result = $mysqli->prepare("$sql");
$result->execute();
$result->store_result();
$result->bind_result($settings);
while ($result->fetch()) {
  var_dump(json_decode($settings,true));
}

$result = $mysqli->prepare("SELECT q_id,settings from  questions WHERE questions.q_type = 'calculation';");
$result->execute();
$result->store_result();
$result->bind_result($qid,$settings);
while ($result->fetch()) {
  $qids[$qid] = $settings;
}
if (!isset($qids) or count($qids)==0) {
  exit();
}
var_dump($qids);
$vars = array('$A','$B','$C','$D','$E','$F','$G','$H','$I','$J','$K','$L');

foreach($qids as $qid=>$settings) {

  print $qid . "   ";
  $result = $mysqli->prepare("SELECT option_text,correct,id_num,marks_correct,marks_incorrect,marks_partial from  options WHERE o_id=? order by id_num;");
  $result->bind_param('i',$qid);
  $result->execute();
  $result->store_result();
  $result->bind_result($optiontext,$correct,$id_num,$marks_correct,$marks_incorrect,$marks_partial);
  $settings=json_decode($settings,true);
  $changed=false;
  $loc=0;
  unset($optionids);
  $optionids=array();
  while ($result->fetch()) {
    $optionids[]=$id_num;
    $changed=true;
    $opts=explode(',',$optiontext);
$settings['vars'][$vars[$loc]]['min']=$opts[0];
$settings['vars'][$vars[$loc]]['max']=$opts[1];
$settings['vars'][$vars[$loc]]['inc']=$opts[2];
$settings['vars'][$vars[$loc]]['dec']=$opts[3];
$ansdat['formula']=$correct;
    $settings['marks_correct']=$marks_correct;
    $settings['marks_incorrect']=$marks_incorrect;
    $settings['marks_partial']=$marks_partial;

    $ansdat['units']=$settings['units'];

    $sql="DELETE from options where id_num=?";
    $delete = $mysqli->prepare($sql);
    print $mysqli->error;
    $delete->bind_param('i', $id_num);

    $delete->execute();
    $loc++;
    print "$id_num :: ";
  }
  if (!isset($settings['strictdisplay'])) {
    $settings['strictdisplay']=false;
  }
  if (!isset($settings['strictzeros'])) {
    $settings['strictzeros']=false;
  }

  if (!isset($settings['dp'])) {
    if (!isset($settings['answer_decimals'])) {
      $settings['dp']=0;
    } else {
      $settings['dp']=$settings['answer_decimals'];
      unset($settings['answer_decimals']);
    }

  }

  if (!isset($settings['fulltoltyp'])) {
    $rep='#';
    if(strpos($settings['tolerance_full'],'%') !== false){
      $settings['tolerance_full']=substr($settings['tolerance_full'],0,strpos($settings['tolerance_full'],'%'));
      $rep='%';
    }
    $settings['fulltoltyp'] = $rep;

  }
  if (!isset($settings['parttoltyp'])) {
    $rep='#';
    if(strpos($settings['tolerance_partial'],'%') !== false){
      $settings['tolerance_partial']=substr($settings['tolerance_partial'],0,strpos($settings['tolerance_partial'],'%'));
      $rep='%';
    }
    $settings['parttoltyp'] = $rep;
  }

  if (!isset($settings['marks_unit'])) {
    $settings['marks_unit']=0;
  }

  if (!isset($settings['show_units'])) {
    $settings['show_units'] = true;
  }

  $settings['answers'][] = $ansdat;
  unset($settings['units']);

  $sql="UPDATE questions set settings=?,q_type='enhancedcalc' where q_id=?";
  $update = $mysqli->prepare($sql);
  $settings=json_encode($settings);
  $update->bind_param('si',$settings, $qid);
  $update->execute();
  var_dump($settings);
  print "<BR>";
}


?>
</body>
</html>

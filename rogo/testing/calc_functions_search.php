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
	$details = array();
	$funcount = array();
	$phpfunc = array('abs','acos','acosh','asin','asinh','atan2','atan','atanh','ceil','cos','cosh','deg2rad','exp','expm1','floor','fmod','log10','log1p','log','max','min','pi','pow','round','sin','sinh','sqrt','tan','tanh');
	
  $result = $mysqli->prepare("SELECT correct FROM options INNER JOIN questions ON options.o_id=questions.q_id WHERE questions.q_type = 'calculation';");
  $result->execute();
  $result->store_result();
  $result->bind_result($correct);
  while ($result->fetch()) {
    $details[] = $correct;
  }
  $result->close();
	
	foreach($details as $correct){
		$correct = preg_replace('/[0-9|+-:\/*\'\\\\^)=!\{\}\[\]]+/','',$correct);
		$correct = preg_replace('/\$[A-J]/','',$correct);
		$correct = preg_replace('/[\(\(]+/','(',$correct);
		$correct = preg_replace('/\n|\r/','',$correct);
		$correct = strtolower ($correct);
		$functions = explode('(',$correct);
		foreach($functions as $func) {
			$func = preg_replace('/^[ ]+/','',$func);
			$func = preg_replace('/[ ]+$/','',$func);
			if (isset($funcount[$func])) {
				$funcount[$func]++;
			} else {
				$funcount[$func] = 1;
			}
		}
	}
	arsort($funcount);
	
	echo '<table border="1"><tbody>';
	foreach ($funcount as $key => $val) {
		$col = '#A00'; if (in_array($key,$phpfunc)) $col = '#0A0';
		if ($key!='') echo "<tr style='color:$col;'><td>$val</td><td>$key</td></tr>";
	}
	echo '</tbody></table>';
?>
</body>
</html>

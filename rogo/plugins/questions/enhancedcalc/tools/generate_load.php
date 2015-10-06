<html>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$root = str_replace('/plugins/questions/enhancedcalc/tools', '/', str_replace('\\', '/', dirname(__FILE__)));

include_once $root . 'include/load_config.php';

require $configObject->get('cfg_web_root') . '/plugins/questions/enhancedcalc/enhancedcalc.class.php';

$settings = '{"strictdisplay":"on","strictzeros":true,"dp":"2","tolerance_full":"2","fulltoltyp":"%","tolerance_partial":"5","parttoltyp":"#","marks_partial":"0.5","marks_incorrect":"0","marks_correct":"1","marks_unit":0,"show_units":true,"answers":[{"formula":"$A - $B","units":"mm"},{"formula":"($A - $B)\/1000","units":"m"}],"vars":{"$A":{"min":"1000","max":"2000","inc":"5","dec":"0"},"$B":{"min":"1000","max":"100000","inc":"5","dec":"0"}}}';
$uans[1] = '{"vars":{"$A":"182.0","$B":"88.0"},"uans":"94.00","uansunit":"mm"}';
$uans[2] = '{"vars":{"$A":"182.0","$B":"88.0"},"uans":"92.00","uansunit":"mm"}';
$uans[3] = '{"vars":{"$A":"182.0","$B":"88.0"},"uans":"90.00","uansunit":"mm"}';
$uans[4] = '{"vars":{"$A":"182.0","$B":"88.0"},"uans":"70.00","uansunit":"mm"}';

//$configObj = new Config();
$q = new EnhancedCalc($configObject);
$q->set_settings($settings);

$mtime = microtime(); 
$mtime = explode(' ', $mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 

echo "<h1>Marking 500 User answers</h1>";
$i = 0;
while($i <= 500 ) {
  $q->set_useranswer($uans[array_rand($uans)]);
  $q->calculate_user_mark();
  echo "<br/> $i ::" . $q->qmark . "<br/>";
  $i++;
}

$mtime = microtime(); 
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 
echo '<h1>Script execution took ' .$totaltime. ' seconds</h1>';
?>
</html>
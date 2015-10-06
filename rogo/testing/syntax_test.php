<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>syntax test</title>
	<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
	<script>
		function chide(cl) {
			if (cl.indexOf(';')>-1) {
				cle = cl.split(';')
				for (var i=0;i<cle.length;i++) {
					$("._"+cle[i]).hide();
					$("#h"+cle[i]).hide();
					$("#s"+cle[i]).show();
				}
			} else {
				$("._"+cl).hide();
				$("#h"+cl).hide();
				$("#s"+cl).show();
			}
		}
		
		function cshow(cl) {
			if (cl.indexOf(';')>-1) {
				cle = cl.split(';')
				for (var i=0;i<cle.length;i++) {
					$("._"+cle[i]).show();
					$("#h"+cle[i]).show();
					$("#s"+cle[i]).hide();
				}
			}else{
				$("._"+cl).show();
				$("#h"+cl).show();
				$("#s"+cl).hide();
			}
		}
	</script>
</head>
<body>
<a href='https://suivarro.nottingham.ac.uk/trac/rogo/wiki/codeStandards'>codeStandards</a>
<?php


//exclusion list
$excluded = explode("|",  ".|..|.ds_store|.svn|.htaccess|help|media|tools|artwork");

//set of rules: short form, correct form, similar but other
$parts_table = Array(Array());$parts_index = 0;
$parts_table[$parts_index++] = Array('else','} else {','else if|elseif','','','','');
$parts_table[$parts_index++] = Array('if','if (','if !|if_','','','','');
$parts_table[$parts_index++] = Array('true','true','-','','','','');
$parts_table[$parts_index++] = Array('br','<br />','-','','','',''); //http://dev.w3.org/html5/html-author/#the-br-element
$parts_table[$parts_index++] = Array('switch','switch (','-','','','','');
$parts_table[$parts_index++] = Array('elseif','elseif (','-','','','','');
$parts_table[$parts_index++] = Array('.',' . ','.=|..|.php|.js|.swf|www.|.com|.gif','','','','dot');
$parts_table[$parts_index++] = Array('=',' = ','==|===|!=|>=|<=|+=|-=|=!|=-|=+','','','','subst');
$parts_table[$parts_index++] = Array('==',' == ','===|!==','','','','compr');
foreach ($parts_table as $pk => $pt) if ($pt[6]=='') $parts_table[$pk][6]=$pt[0];

//calculate lengths
foreach ($parts_table as $part_index => $part_element) {
  $parts_table[$part_index][3] = strpos($part_element[1],$part_element[0]);
  $parts_table[$part_index][4] = $parts_table[$part_index][5] = strlen($part_element[1]);
  foreach (explode('|',$part_element[2]) as $pp) {
    if ($parts_table[$part_index][5]<strlen($pp)) $parts_table[$part_index][5]=strlen($pp);
  }
}

//function for recursive files search
function file_array($thispath, $exclude, $recurse) {
  global $files,$paths;

  $thispath = rtrim($thispath, "/") . "/";
  $result = array();
  $folder_handle = opendir($thispath);
  while(false !== ($filename = readdir($folder_handle))) {
    if (!in_array(strtolower($filename), $exclude)) {
      if (is_dir($thispath . $filename . "/")) {
        array_push($paths, Array($thispath , $filename));
        if ($recurse) $result[] = file_array($thispath . $filename . "/", $exclude, $recurse);
      } else {
        if (strpos($filename,'.php',0)>0 || strpos($filename,'.inc',0)>0  || strpos($filename,'.js',0)>0) {
          array_push($files, $thispath . $filename);
        }
      }
    }
  }
  return $result;
}

$thispath=getcwd();
if (isset($_GET['path'])) $thispath=$_GET['path'];
$rcs='0';
if (isset($_GET['rcs'])) $rcs=$_GET['rcs'];

$diff_table = Array(Array());
$diff_index = 0;

$thispath=rtrim($thispath, "\\");

//searching for files
$files = Array();
$paths = Array(Array());
$thispaths = Array();

$thispaths = file_array($thispath, $excluded, ($rcs==1)?true:false);

//display folders
echo "<table border=1 cellpadding=5 cellspacing=1><tr><td>";
if ($rcs==1) {
  echo "<a href='?path=".$thispath."&rcs=0'>[search non-recursively]</a><br />";  
} else {
  echo "<a href='?path=".$thispath."&rcs=1'>[search recursively]</a><br />";  
}
echo "<a href='?path=".dirname($thispath)."&rcs=".$rcs."'>..</a><br />";
foreach ($paths as $path) {
  if (isset($path[0])) 
    echo "<a href='?path=".$path[0].$path[1]."&rcs=".$rcs."'>".$path[1]."</a><br />";
}
echo "</td></tr></table>";

//read files
foreach ($files as $filename) {
  $file_point=fopen($filename,"r");
  $file_content=fread($file_point, filesize($filename));
  $file_content=preg_replace('/\/\/.*\n/','�',$file_content); 
	$file_content=preg_replace('/\n/','�',$file_content);
	$file_content=preg_replace('/\t/','  ',$file_content);
	
  fclose($file_point);
  
  foreach ($parts_table as $part_index => $part_element) {
   $pos=1;
   while ($pos) {            
      $pos = strpos($file_content,$part_element[0],$pos+1); //pos of short form
      //cut out the existing string to compare
      $pot = substr($file_content,$pos-$parts_table[$part_index][3],$parts_table[$part_index][5]+$parts_table[$part_index][3]);
      $pot2 = substr($file_content,$pos-$parts_table[$part_index][3]-2,$parts_table[$part_index][5]+$parts_table[$part_index][3]+4);
      $pop = true;
        if (strpos($pot,$part_element[1])>-1) $pop = false;
      foreach (explode('|',$part_element[2]) as $pp) 
        if (strpos($pot,$pp)>-1) $pop = false;
      $poz = true;
      if (ctype_alnum(substr($file_content,$pos-1,1))) $poz = false; 
      if (ctype_alnum(substr($file_content,$pos+strlen($part_element[0]),1))) $poz = false; 
      if ($pop && $pos>-1 && $poz) {
        //calculate the line number
        //echo $pot.'--'.substr($file_content,$pos-1,1).':'.substr($file_content,$pos+strlen($part_element[0]),1).'<br>';
        $line_number = substr_count(substr($file_content,0,$pos),'�')+1;
        $diff_table[$diff_index++] = Array($part_index,$filename,$pos,$pot,$line_number,$part_element[0],$part_element[6]);
      }
    }
  }
}

//display results
echo '<h2>'.$thispath.'</h2>';
$all='all';foreach ($parts_table as $pt) $all.=';'.$pt[6];


echo "<a id=\"hall\" href=\"#\" onClick=\"chide('".$all."'); return false;\">hide all</a> ";
echo "<a id=\"sall\" style=\"display:none\" href=\"#\" onClick=\"cshow('".$all."'); return false;\">show all</a> ";

foreach ($parts_table as $pt) {
	echo "<a id=\"h".$pt[6]."\" href=\"#\" onClick=\"chide('".$pt[6]."'); return false;\"><strong>".$pt[6]."</strong></a> ";
	echo "<a id=\"s".$pt[6]."\" style=\"display:none;text-decoration: line-through;\" href=\"#\" onClick=\"cshow('".$pt[6]."'); return false;\"><strong>".$pt[6]."</strong></a> ";
}
echo '<ol>';
foreach ($diff_table as $df) {
  if (isset($df[0])) {
    echo '<li class="_'.$df[6].'">';
    echo '<em>'.$df[1].'</em>';
    echo ' #'.$df[4];
    echo ' <strong>[...'.preg_replace('/</','&lt;',$df[3]).'...]</strong> ';
    echo '<sup>'.$df[5].'</sup>';
    echo '</li>';
  }
}
echo '</ol>';
?>
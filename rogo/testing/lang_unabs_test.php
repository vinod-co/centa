<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
   <title>Lang unabstracted string search</title>
	<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
	<script>
		function chide(cl) {
			if (cl.indexOf(';')>-1) {
				cle = cl.split(';')
				for (var i=0;i<cle.length;i++) {
					$("."+cle[i]).hide();
					$("#h"+cle[i]).hide();
					$("#s"+cle[i]).show();
				}
			}else{
				$("."+cl).hide();
				$("#h"+cl).hide();
				$("#s"+cl).show();
			}
		}
		
		function cshow(cl) {
			if (cl.indexOf(';')>-1) {
				cle = cl.split(';')
				for (var i=0;i<cle.length;i++) {
					$("."+cle[i]).show();
					$("#h"+cle[i]).show();
					$("#s"+cle[i]).hide();
				}
			}else{
				$("."+cl).show();
				$("#h"+cl).show();
				$("#s"+cl).hide();
			}
		}
	</script>
</head>

<?php


//exclusion list
$excluded = explode("|",  ".|..|.ds_store|.svn|.htaccess|help|media|tools|artwork|testing|exports|imports|lang|updates");

//set of rules: type, short form, searched form, similar but other ....  position translation from short to long form, short form length, max length of option
//spaces will be ignored
$parts_table = Array(Array());$parts_index = 0;
$common_parts = '{|*|$|sprintf|htmlentities|strip_tags|substr|mb_|number_format|get_string|formatsec|display_|demo_|str|round|0|1|2|3|4|5|6|7|8|9|write_string|\'.|".';
$parts_table[$parts_index++] = Array(1, 'value', '="|=\'', '"|<|;|t|f|true|false|opaque|showall|allways|high|#|\|'.$common_parts);
$parts_table[$parts_index++] = Array(1, 'title', '="|=\'', '"|<|;|t|f|true|false|high|#|\|'.$common_parts);
$parts_table[$parts_index++] = Array(1, 'echo', ' "| \'', ''.$common_parts);

$parts_table[$parts_index++] = Array(2, 'title', ' |>', '<?|Rog|\'\.$"|'.$common_parts);
$parts_table[$parts_index++] = Array(2, 'div', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'span', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'p', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'h1', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'h2', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'h3', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'h4', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'li', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'option', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'td', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'strong', ' |>', ''.$common_parts);
$parts_table[$parts_index++] = Array(2, 'em', ' |>', ''.$common_parts);

$parts_table[$parts_index++] = Array(3, 'confirm', '(', 'lang[|tinyMCE|msg|'.$common_parts);
$parts_table[$parts_index++] = Array(3, 'alert', '(', 'lang[|tinyMCE|msg|'.$common_parts);


//calculate lengths
foreach($parts_table as $part_index => $part_element) {
	$parts_table[$part_index][4] = strlen($part_element[1]);

  if ($parts_table[$part_index][0]==1) $parts_table[$part_index][5] = 0;
  if ($parts_table[$part_index][0]==2) $parts_table[$part_index][5] = 1;  
  if ($parts_table[$part_index][0]==3) $parts_table[$part_index][5] = 0;  
	
	$parts_table[$part_index][6] = 0;
  foreach (explode('|',$part_element[3]) as $pp) {
    if ($parts_table[$part_index][6]<strlen($pp)) $parts_table[$part_index][6]=strlen($pp);
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
        if ((strpos($filename,'.php',0)>0 || strpos($filename,'.inc',0)>0)) {
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
$filesnostring = Array();

$thispaths = file_array($thispath, $excluded, ($rcs==1)?true:false);

//display folders
echo "<table border=1 cellpadding=5 cellspacing=1><tr><td>";
if ($rcs==1) {
  echo "<a href='?path=".$thispath."&rcs=0'>[search non-recursively]</a><br />";  
} else {
  echo "<a href='?path=".$thispath."&rcs=1'>[search recursively]</a><br />";  
}
echo "<a href='?path=".dirname($thispath)."&rcs=".$rcs."'>..</a><br />";
foreach($paths as $path) {
  if (isset($path[0])) 
    echo "<a href='?path=".$path[0].$path[1]."&rcs=".$rcs."'>".$path[1]."</a><br />";
}
echo "</td></tr></table>";



//read files
foreach($files as $filename) {
  $file_point=fopen($filename,"r");
  $file_content=fread($file_point, filesize($filename));
  $file_content=preg_replace('/\n/','~',$file_content);
  fclose($file_point);
  
  if (!strpos($file_content,'$string')>-1) array_push($filesnostring,$filename);
  
  foreach($parts_table as $part_index => $part_element) {
		$pos1=1;
    while ($pos1) {
			$post1 = strlen($file_content);
			foreach (explode('|',$part_element[2]) as $pp) {
				if ($part_element[0]==1) $part_searched = $part_element[1].$pp;
				if ($part_element[0]==2) $part_searched = '<'.$part_element[1];
				if ($part_element[0]==3) $part_searched = $part_element[1].$pp;
				$post0 = strpos($file_content,$part_searched,$pos1+1); 
				if ($post0!=false && $post0<$post1) $post1 = $post0;
			}
			$pos1 = ($post1 == strlen($file_content))?false:$post1;
			if ($pos1) {
				$trs = strlen($part_searched)+1;
				$pos2 = strpos($file_content,'~',$pos1+$trs);
				
				if ($part_element[0]==1) $part_searched = '"';
				if ($part_element[0]==2) $part_searched = '</'.$part_element[1];
				if ($part_element[0]==3) $part_searched = ')';
				$pos3 = strpos($file_content,$part_searched,$pos1+$trs);
  			if ($pos3!=false && $pos3<$pos2) $pos2 = $pos3;
				
				//cut out the existing string to compare
				$pot0 = mb_substr($file_content,$pos1,$pos2-$pos1);
				$pot1 = $pot0 = preg_replace('/[\t\n\r]/','',$pot0); //eol
				$pot1 = preg_replace('/ /','',$pot1); //spaces
				$pot1 = preg_replace('/&nbsp;/','',$pot1); //spaces
				$pot1 = preg_replace('/\\\n/','',$pot1); //eol's
				$pot1 = preg_replace('/\\\""./','\"',$pot1); //backslashe with quot mark and period
				$pot1 = preg_replace('/\\\"/','"',$pot1); //backslashe with quot mark
								
				$pop = false;
				//included forms
				foreach (explode('|',$part_element[2]) as $pp1) {
					if (strpos($pot1,preg_replace('/ /','',$part_element[1].$pp1))>-1) $pop = true;
				}
				
				//excluded forms for type 1
				if ($part_element[0]==1) {
					foreach (explode('|',$part_element[2]) as $pp1) {
						foreach (explode('|',$part_element[3]) as $pp2) {
							//if (strpos($pot1,$part_element[1].$pp1.$pp2)>-1) $pop = false;
							if (strpos($pot1,preg_replace('/ /','',$part_element[1].$pp1.$pp2))>-1) $pop = false;
							//if ($pp2=='write_string' && strpos($pot1,$pp2)>-1) var_dump($pot1.' >>> '.$pp2);
						}
					}
  			
				$pot0 = strip_tags($pot0);
				$pot1 = strip_tags($pot1);
				$pot1 = preg_replace('/[\["\';.?)(]/', '',$pot1);
				if (substr($pot1,$trs-1)=='') $pop = false;					
				}

				//excluded forms for type 2				
				if ($part_element[0]==2) {
					$pot1 = $pot0 = strip_tags(substr($pot0,strpos($pot0,'>')+1));
					$pot1 = preg_replace('/ /','',$pot0); //spaces
					$pot1 = preg_replace('/&nbsp;/','',$pot1);
					$pot1 = preg_replace('/\\\n/','',$pot1);
					$pot1 = preg_replace('/\\\t/','',$pot1);
						$pot1 = preg_replace('/"./','%',$pot1);
						$pot1 = preg_replace('/\'./','%',$pot1);
					$pot1 = preg_replace('/[\["\';.?)(]/', '',$pot1);
						$pot1 = preg_replace('/%/','".',$pot1);
						$pot1 = preg_replace('/%/','\'.',$pot1);
					//$pot1 = preg_replace('/[\[;?)(]/', '',$pot1);
					$pot1 = preg_replace('/[\s]/','',$pot1);
			
					$trs = 1; 
					foreach (explode('|',$part_element[3]) as $pp2) {
						if ($pot1=='' || strpos($pot1,$pp2)===0) $pop = false;
					}
				}
				
				//excluded forms for type 3
				if ($part_element[0]==3) {
					$pot1 = $pot0 = strip_tags(substr($pot0,strpos($pot0,'(')+1));
					$pot1 = preg_replace('/ /','',$pot0); //spaces
					$pot1 = preg_replace('/&nbsp;/','',$pot1);
					$pot1 = preg_replace('/\\\n/','',$pot1);
					$pot1 = preg_replace('/\\\t/','',$pot1);
					$pot1 = preg_replace('/[\["\';.?)(]/', '',$pot1);
					$pot1 = preg_replace('/[\s]/','',$pot1);
			
					$trs = 1; 
					foreach (explode('|',$part_element[3]) as $pp2) 
						if ($pot1=='' || strpos($pot1,$pp2)===0) $pop = false;
				}

				if ($pop) {
					//calculate the line number
					$line_number = substr_count(substr($file_content,0,$pos1),'~')+1;
					$diff_table[$diff_index++] = Array($part_index,$filename,$pos1,substr($pot0,$trs-1),$line_number,$part_element[0],$part_element[1]);
				}
			}
    }
  }
}

//display results
echo '<h2>'.$thispath.'</h2>';
$all='all';foreach ($parts_table as $pt) $all.=';'.$pt[0].$pt[1];


echo "<a id=\"hall\" href=\"#\" onClick=\"chide('".$all."'); return false;\">hide all</a> ";
echo "<a id=\"sall\" style=\"display:none\" href=\"#\" onClick=\"cshow('".$all."'); return false;\">show all</a> ";

foreach ($parts_table as $pt) {
	echo "<a id=\"h".$pt[0].$pt[1]."\" href=\"#\" onClick=\"chide('".$pt[0].$pt[1]."'); return false;\"><strong>".$pt[1]."</strong></a> ";
	echo "<a id=\"s".$pt[0].$pt[1]."\" style=\"display:none;text-decoration: line-through;\" href=\"#\" onClick=\"cshow('".$pt[0].$pt[1]."'); return false;\"><strong>".$pt[1]."</strong></a> ";
}
echo '<ol>';
foreach($diff_table as $df) {
  if (isset($df[0])) {
    echo '<li class="'.$df[5].$df[6].'">';
    echo '<em>'.$df[1].'</em>';
    echo ' #'.$df[4];
    echo ' <strong>['.preg_replace('/</','&lt;',$df[3]).']</strong> ';
    echo '<sup>'.preg_replace('/</','',$df[6]).'</sup>';
    echo '</li>';
  }
}
echo '</ol>';
?>
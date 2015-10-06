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
* @copyright Copyright (c) 2013 The University of Nottingham
* @package
*/

  require '../../include/staff_auth.inc';
?>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="content-type" content="text/html;charset=<?php echo $configObject->get('cfg_page_charset') ?>" />
  
  <title>Buttons</title>

  <link rel="stylesheet" type="text/css" href="../../css/body.css" />
  <style type="text/css">
    html {height:100%}
    body {height:100%; background-color:#F0F0F0; margin-top:4px; margin-bottom:2px; margin-left:4px; margin-right:4px}
		.tab {padding-left:10px; height:25px; cursor:default}
    .tab:hover {background-color: #FFE7A2}
    .tabon {padding-left:10px; height:25px; cursor:default; background-color:#FFBD69}
		.grey {color:#909090}
  </style>

	<script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
  <script>
    var selectedButton = 'unused';
  
    function buttonclick(sectionID, scriptName) {
      parent.qlist.iframeurl.location = scriptName;
      parent.qlist.previewurl.location = 'preview_default.php';
      
      $('.tab').each(function() {
        $(this).removeClass('tabon');
      });
      $('.tabon').each(function() {
        $(this).removeClass('tabon');
        $(this).addClass('tab');
      });
 			$('#button_'+sectionID).removeClass('tab');
 			$('#button_'+sectionID).addClass('tabon');
      
      
      selectedButton = sectionID;
    }

    function buttonover(buttonID) {
      if (buttonID != selectedButton) {
        $('#button_'+buttonID).css('backgroundColor','#FFE7A2');
      }
    }

    function buttonout(buttonID) {
      if (buttonID != selectedButton) {
        $('#button_'+buttonID).css('backgroundColor','white');
      }
    }
  </script>
</head>
<body>

<table cellspacing="0" cellpadding="0" style="font-size:90%; width:126px; height:99%; background-color:white; border:1px solid #828790">
<tr><td style="vertical-align:top; text-align:center">

<table cellspacing="0" cellpadding="0" style="font-size:90%; width:144px; text-align:left">
<tr><td id="button_unused" class="tabon" onclick="buttonclick('unused','add_questions_list.php?type=unused')"><?php echo $string['myunused'] ?></td></tr>
<tr><td id="button_alphabetic" class="tab" onclick="buttonclick('alphabetic','add_questions_list.php?type=all')"><?php echo $string['allmyquestions'] ?></td></tr>
<tr><td id="button_keywords" class="tab" onclick="buttonclick('keywords','add_questions_keywords_frame.php')"><?php echo $string['bykeywords'] ?></td></tr>
<tr><td id="button_status" class="tab" onclick="buttonclick('status','add_questions_by_status.php')"><?php echo $string['bystatus'] ?></td></tr>
<tr><td id="button_papers" class="tab" onclick="buttonclick('papers','add_questions_paper_types.php')"><?php echo $string['bypaper'] ?></td></tr>
<?php
  $user_modules = $userObject->get_staff_modules();

  if (count($user_modules) > 0) {
		echo '<tr><td id="button_team" class="tab" onclick="buttonclick(\'team\',\'add_questions_team_list.php\')">' . $string['byteam'] . '</td></tr>';
	} else {
		echo '<tr><td id="button_team" class="tab grey">' . $string['byteam'] . '</td></tr>';
	}
?>
<tr><td id="button_search" class="tab" onclick="buttonclick('search','add_questions_list_search.php')"><?php echo $string['search'] ?></td></tr>
</table>

</td></tr>
</table>

</body>
</html>

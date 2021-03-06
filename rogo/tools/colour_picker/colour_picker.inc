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
* Displays the popup DIV which contains colour swatches for selection.
* Can further call 'more_colours.php' in a new window for event more colours.
*
* @author Simon Wilkinson
* @version 1.0
* @copyright Copyright (c) 2014 The University of Nottingham
* @package
*/
?>
<script>
  var textBox = '';

  function showPicker(pickerID, evt) {
    textBox = pickerID;

    var pickerSpan = $('#span_' + pickerID);
		var position = pickerSpan.position();    
    
    $('#picker').css('top', 20 + position.top + 'px');
    $('#picker').css('left', position.left + 'px');
    $('#picker').show();

    evt.cancelBubble = true;
  }
  
  function setColor(color, page) {
    $('#' + textBox).val(color);
    $('#span_' + textBox).css('background-color', color);
    $('#picker').hide();
    
    if (page == '<?php echo $configObject->get('cfg_root_path') ?>/users/details.php') {
      updateAccessDemo();
    }
  }
  
  function hidePicker() {
    $('#picker').hide();
  }
  
  function moreColours() {
    notice=window.open("<?php echo $configObject->get('cfg_root_path') ?>/tools/colour_picker/more_colours.php?swatch=" + textBox + "","colours","width=450,height=370,left="+(screen.width/2-225)+",top="+(screen.height/2-185)+",scrollbars=no,toolbar=no,location=no,directories=no,status=yes,menubar=no,resizable");
    if (window.focus) {
      notice.focus();
    }
  }
</script>

<div id="picker" style="background-color:white; border:1px solid #808080; float:left; padding:1px; position:absolute; display:none; font-size:100%">
<table cellspacing="0" cellpadding="0" style=\"background-color:#FAFAFA; font-size:100%; border-collapse:collapse\">
<?php
  // Theme colours
  $row[1] = array('#FFFFFF','#000000','#EEECE1','#1F497D','#4F81BD','#C0504D','#9BBB59','#8064A2','#4BACC6','#F79646');
  $row[2] = array('#F2F2F2','#7F7F7F','#DDD9C3','#C6D9F0','#DBE5F1','#F2DCDB','#EBF1DD','#E5E0EC','#DBEEF3','#FDEADA');
  $row[3] = array('#D8D8D8','#595959','#C4BD97','#8DB3E2','#B8CCE4','#E5B9B7','#D7E3BC','#CCC1D9','#B7DDE8','#FBD5B5');
  $row[4] = array('#BFBFBF','#3F3F3F','#938953','#548DD4','#95B3D7','#D99694','#C3D69B','#B2A2C7','#92CDDC','#FAC08F');
  $row[5] = array('#A5A5A5','#262626','#494429','#17365D','#366092','#953734','#76923C','#5F497A','#31859B','#E36C09');
  $row[6] = array('#7F7F7F','#0C0C0C','#1D1B10','#0F243E','#244061','#632423','#4F6128','#3F3151','#205867','#974806');
  // Standard colours
  $row[7] = array('#C00000','#FF0000','#FFC000','#FFFF00','#92D050','#00B050','#00B0F0','#0070C0','#002060','#7030A0');
  
  echo "<tr style=\"height:4px\"><td colspan=\"21\"></td></tr>";
  echo "<tr><td colspan=\"21\" style=\"padding-left:5px; background-color: #EEEEEE; font-size:70%; font-weight:bold\">".$string['themecolours']."</td></tr>";
  echo "<tr style=\"height:4px\"><td colspan=\"21\"></td></tr>";
  echo "<tr style=\"height:14px\">\n<td style=\"width:5px\"></td>";
  foreach ($row[1] as $colour) {
    echo "<td style=\"border: 1px solid #C5C5C5; width:12px; background-color:$colour\" onclick=\"setColor('$colour', '" . $_SERVER['PHP_SELF'] . "')\"></td><td style=\"width:5px\"></td>";
  }
  echo "</tr>\n";
  echo "<tr style=\"height:8px\"><td colspan=\"21\"></td></tr>";
  
  for ($row_no=2; $row_no<=6; $row_no++) {
    if ($row_no == 2) {
      $border_top = 'border-top: 1px solid #C5C5C5; ';
    } else {
      $border_top = '';
    }
    if ($row_no == 6) {
      $border_bottom = 'border-bottom: 1px solid #C5C5C5; ';
    } else {
      $border_bottom = '';
    }
    echo "<tr style=\"height:12px\">\n<td style=\"width:5px\"></td>";
    foreach ($row[$row_no] as $colour) {
      echo "<td style=\"" . $border_top . $border_bottom. "border-left: 1px solid #C5C5C5; border-right: 1px solid #C5C5C5; width:12px; background-color:$colour\" onclick=\"setColor('$colour', '" . $_SERVER['SCRIPT_NAME'] . "')\"></td><td style=\"width:5px\"></td>";
    }
    echo "</tr>\n";
  }
  echo "<tr style=\"height:4px\"><td colspan=\"21\"></td></tr>";
  echo "<tr><td colspan=\"21\" style=\"padding-left:5px; background-color: #EEEEEE; font-size:70%; font-weight:bold\">".$string['standardcolours']."</td></tr>";
  echo "<tr style=\"height:4px\"><td colspan=\"21\"></td></tr>";
  
  echo "<tr style=\"height:14px\">\n<td style=\"width:5px\"></td>";
  foreach ($row[7] as $colour) {
    echo "<td style=\"border: 1px solid #C5C5C5; width:12px; background-color:$colour\" onclick=\"setColor('$colour', '" . $_SERVER['SCRIPT_NAME'] . "')\"></td><td style=\"width:5px\"></td>";
  }
  echo "</tr>\n";
  echo "<tr style=\"height:4px\"><td colspan=\"21\" style=\"border-bottom: 1px solid #C5C5C5\"></td></tr>";
  echo "<tr><td colspan=\"2\" style=\"padding-top:1px; padding-bottom:1px; background-color:#E9EEEE\"><img src=\"{$configObject->get('cfg_root_path')}/artwork/palette.png\" width=\"16\" height=\"16\" border=\"0\" onclick=\"moreColours();\" /></td><td style=\"border-left: 1px solid #C5C5C5; font-size:80%\" colspan=\"19\" onclick=\"moreColours();\">&nbsp;".$string['more']."</td></tr>";
?>
</table>
</div>

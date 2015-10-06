function UpdateClock( hours, minutes, seconds) {
  KillClock();
  
  if ( hours == 0 ){
    hours   = '';
    minutes = ( ( minutes  < 10 ) ? "0" : "" ) + minutes;
  } else {
    hours   = ( ( hours < 10 ) ? "0" : "" ) + hours;
    minutes = ( ( minutes  < 10 ) ? ":0" : ":" ) + minutes;
  }
  seconds = ( ( seconds < 10 ) ? ":0" : ":" ) + seconds;

  $('#theTime').html("" + hours + minutes + seconds);
}


//BP Performs countdown. Saves if counter has reached 0
function UpdateTimerWithRemainingTime(remaining_time, close) {
  
  minutes = Math.floor( remaining_time / 60 );
  minutes = Math.round( minutes );
  seconds = remaining_time % 60;
  
  UpdateClock( 0, minutes, seconds);
  
  if (remaining_time == 0 && close == true) {
    KillClock();
    forceSave();
    return;
  }
  if( remaining_time > 0 ){
    remaining_time = remaining_time -1;
  }
  clockID = setTimeout( "UpdateTimerWithRemainingTime( " + remaining_time + ", " + close + " )", 1000 );
}

function UpdateClockWithCurrentTime() {

  var tDate   = new Date();
  
  var hours   = tDate.getHours();
  var minutes = tDate.getMinutes();
  var seconds = tDate.getSeconds();
  
  UpdateClock(hours, minutes, seconds);
  
  clockID = setTimeout("UpdateClockWithCurrentTime()", 1000);
}

function StartTimer(remaining_time, close) {
  clockID = setTimeout("UpdateTimerWithRemainingTime(" + remaining_time + ", " + close + " )", 500);
}

function StartClock() {
  clockID = setTimeout("UpdateClockWithCurrentTime()", 500);
}

function KillClock() {
  if (clockID) {
    clearTimeout(clockID);
    clockID  = 0;
  }
}

function MRQ(questionid, part_id, options_total, selectable) {
	var abstainExist = document.getElementById("q" + questionid + "_abstain");
	if (abstainExist != null) {
		$("#q" + questionid + "_abstain").prop("checked", false);
	}
	
  checked_total = 0;
  for (i=1; i<=options_total; i++) {
    currentid = "q" + questionid + "_" + i;
    if ($('#' + currentid).prop("checked")) {
      checked_total++;
    }
  }
  if (checked_total > selectable) {
		alert(lang['msgselectable1'] + ' ' + selectable + ' ' + lang['msgselectable2']);
		$("#q" + questionid + "_" + part_id).prop("checked", false);
  }
}

function MRQabstain(questionid, options_total) {
  for (i=1; i<=options_total; i++) {
		$("#q" + questionid + "_" + i).prop("checked", false);
  }
}

function rankCheck() {
  var sel = $(this).val();    
  var classlist =  '.' + $(this).attr('class').replace(' ', '.');
  var count = 0;
  var loopSel = '';
  
  $(classlist).each(function () {
    loopSel = $(this).val();
    if(loopSel != '0' && loopSel != 'u' && loopSel == sel) count++;
  });
  if (count > 1) {
    alert(lang['msgselectable3'] + ' ' + sel  + lang['msgselectable4']);
    $(this).val('u');
  }
}

function multimatchingCheck(questionid, options_total, selectable) {
  checked_total = 0;
  for (i=0; i<options_total; i++) {
    if (document.getElementById(questionid).options[i].selected == 1) {
      checked_total++;
    }
  }
  tmp_count = 0;
  if (checked_total > selectable) {
    alert(lang['msgselectable1'] + ' ' + selectable + ' ' + lang['msgselectable2']);
	
    for (i=0; i<options_total; i++) {
      if (document.getElementById(questionid).options[i].selected == 1) {
        tmp_count++;
      }
      if (tmp_count > selectable) {
        document.getElementById(questionid).options[i].selected = 0;
      }
    }
  }
}

$(document).ready(function(){

  $('.calc').click(function() {
    if (typeof(calc) == 'object' && calc.closed != true) {
      calc.focus();
    } else {
      calc = window.open("../tools/calc98/jcalc98.php","calculator","width=250,height=364,top=10,left="+($(window).width() - 280)+"scrollbars=no,resizable=no,toolbar=no,location=no,directories=no,status=no,menubar=no");
      if (window.focus) {
        calc.focus();
      }
    }
  });
  
  $('#previous').click(function() {
    $('#button_pressed').val('previous');
  });
  
  $('#finish').click(function() {
    $('#button_pressed').val('finish');
  });
  
  $('.act').click(function() {
    onoff($(this).attr('id'));
  });

  $('.inact').click(function() {
    onoff($(this).attr('id'));
  });

});

function onoff(objID) {
  var parts = objID.split("_");
  var questionID = parts[0];
  var itemID = parts[1];

  if ($('#' + objID).hasClass("act")) {
    $('#' + objID).addClass("inact")
    $('#' + objID).removeClass("act")
    setting = '1';
  } else {
    $('#' + objID).addClass("act")
    $('#' + objID).removeClass("inact")
    setting = '0';
  }
  objID = 'dismiss' + questionID;
  current_value = $('#' + objID).val();
  new_value = current_value.slice(0,itemID-1) + setting + current_value.slice(itemID,current_value.length);
  $('#' + objID).val(new_value);      
}

function write_string(p_string) {
  document.write(p_string);
}

function filterKeypress(event) {
  // There is no situation where a shifted key is valid
  if (event.shiftKey === true || event.altKey === true) {
    event.preventDefault();
    return false;
  }

  // Allow only one .
  if ((event.keyCode == 190    // .
      || event.keyCode == 110)) // . (keypad)
  {
    if ($(event.target).val().indexOf('.') !== -1) {
      event.preventDefault();
    }
    return;
  }
  // Allow - only at start of answer
  if (event.keyCode == 173    // -
      || event.keyCode == 189 // - (IE)
      || event.keyCode == 109) // - (keypad)
  {
    if ($(event.target).val().indexOf('-') !== -1) {
      event.preventDefault();
    }
    return;
  }
  // Allow: backspace, delete, tab and escape
  if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
  // Allow: Ctrl+A
  (event.keyCode == 65 && event.ctrlKey === true) ||
  // Allow: home, end, left, right
  (event.keyCode >= 35 && event.keyCode <= 39)) {
    // let it happen, don't do anything
    return;
  } else {
    // Ensure that it is a number and stop the keypress
    if (((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ))) {
      event.preventDefault();
      return false;
    }
  }
}

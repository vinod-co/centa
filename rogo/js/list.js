function displayDetails (lineID, event) {
  event.stopPropagation();
  details = window.open("./sys_error_details.php?errorID=" + lineID + "","properties","width=900,height=800,left="+(screen.width/2-450)+",top="+(screen.height/2-400)+",scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
  if (window.focus) {
    details.focus();
  }
}

$(function () {
  $('body').click(deselLine);
});
    
function selLine(lineID, event) {
  $('.highlight').removeClass('highlight');

  $('#menu1a').hide();
  $('#menu1b').show();
  $('#lineID').val(lineID);
     
  $('#' + lineID).addClass('highlight');
  event.cancelBubble = true;
}

function deselLine() {
  $('.highlight').removeClass('highlight');
  
  $('#lineID').val();
  $('#menu1b').hide();
  $('#menu1a').show();
}

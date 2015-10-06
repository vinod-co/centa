var ie  = document.all
var ns6 = document.getElementById&&!document.all
var isMenu  = false ;
var menuSelObj = null ;
var overpopupmenu = false;

function mouseSelect(e) {
  var obj = ns6 ? e.target.parentNode : event.srcElement.parentElement;
  if (isMenu) {
    if (overpopupmenu == false) {
      isMenu = false ;
      overpopupmenu = false;
      $('#menudiv').hide();
      return true ;
    }
    return true ;
  }
  return false;
}

// POP UP MENU
function popMenu(option_no, e) {
  if (!e) var e = window.event;
  var currentX = e.clientX;
  var currentY = e.clientY;
  var scrOfX = $(document).scrollLeft();
  var scrOfY = $(document).scrollTop();
  
  $('#menudiv').show();
  
  top_pos = currentY + scrOfY;
  div_height = $('#menudiv').height() + 6;
  if (top_pos > ($(window).height() + scrOfY - div_height)) {
    top_pos = $(window).height() + scrOfY - div_height;
  }
  $('#menudiv').css('left', e.clientX + scrOfX);
  $('#menudiv').css('top', top_pos);
  
  isMenu = true;
	cancelBubble(e);
  return false;
}

function cancelBubble(e) {
  var evt = e ? e:window.event;
	if (evt.stopPropagation)		evt.stopPropagation();
	if (evt.cancelBubble!=null)	evt.cancelBubble = true;
}

$(document).ready(function() {
  $('#menudiv').mouseover(function() {
    overpopupmenu=true;
  });
  
  $('#menudiv').mouseout(function() {
    overpopupmenu=false;
  });

});
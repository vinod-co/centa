
function scrollXY() {
  $('#scrOfY').val($(window).scrollTop());
}

$(document).ready(function(){

  $(window).scroll(function() {
    scrollXY();
  });

});    
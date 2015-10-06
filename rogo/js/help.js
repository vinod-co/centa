$(function() {
  $('#toc').scrollTop(scrOfY);
  
  $('#back').click(function() {
    history.back();
  });
  
  $('#forwards').click(function() {
    history.forward();
  });
  
  $('#home').click(function() {
    window.location = "index.php?id=1";
  });
  
  $('#delete').click(function() {
    if (confirm(langStrings['confirmdelete'])) {
      window.location = 'delete_page.php?id=' + id;
    }
  });
  
  $('#new').click(function() {
    window.location = 'new_page.php';
  });
  
  $('#pointer').click(function() {
    window.location = 'new_pointer.php';
  });
  
  $('#edit').click(function() {
    window.location = 'edit_page.php?id=' + id;
  });
  
  $('#recycle_bin').click(function() {
    window.location = 'recycle_bin.php';
  });
  
  $('#info').click(function() {
    window.location = 'stats.php';
  });  
    
  $('#search').click(function() {
    window.location = 'search.php?searchstring=' + $('#searchbox').val();
  });  
    
  $('.gototop').click(function() {
    $("#contents").animate({ scrollTop: 0 }, "slow");
  });

  $('.book').click(function() {
    var str = $(this).attr('id');
    var sectionID = str.substr(4);

    $('#submenu' + sectionID).toggle();

    icon = ($('#button' + sectionID).attr('src') == '../open_book.png') ? '../closed_book.png' : '../open_book.png';
    $('#button' + sectionID).attr('src', icon);
  });

  $('.pointer_book').click(function() {
    var str = $(this).attr('id');
    var sectionID = str.substr(12);

    $('#pointer_submenu' + sectionID).toggle();

    icon = ($('#pointer_button' + sectionID).attr('src') == '../open_book.png') ? '../closed_book.png' : '../open_book.png';
    $('#pointer_button' + sectionID).attr('src', icon);
  });

  $('#toc div.page').click(function() {
    var str = $(this).attr('id');
    window.location = 'index.php?id=' + str.substr(5) + '&scrOfY=' + $('#toc').scrollTop();
  });
  
});
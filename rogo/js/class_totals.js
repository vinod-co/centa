function viewScript() {
  $('#menudiv').hide();
  if ($('#metadataID').val() != '') {
    var winwidth = screen.width-80;
    var winheight = screen.height-80;
    window.open("../paper/finish.php?id=" + crypt_name + "&userID=" + $('#userID').val() + "&metadataID=" + $('#metadataID').val() + "&log_type=" + $('#log_type').val() + "&percent=" + $('#percent').val() + "","paper","width="+winwidth+",height="+winheight+",left=30,top=20,scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
  }
}

function viewFeedback() {
  $('#menudiv').hide();
  if ($('#metadataID').val() != '') {
    var winwidth = screen.width-80;
    var winheight = screen.height-80;
    window.open("../students/objectives_feedback.php?id=" + crypt_name + "&userID=" + $('#userID').val() + "&metadataID=" + $('#metadataID').val() + "","feedback","width="+winwidth+",height="+winheight+",left=30,top=20,scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
  }
}

function viewNote(userID, e) {
  $('#menudiv').hide();
  $("#accessDiv").hide();
  if (!e) var e = window.event;
  var currentX = e.clientX;
  var currentY = e.clientY;
  var scrOfX = $(document).scrollLeft();
  var scrOfY = $(document).scrollTop();

  dataSource = "../ajax/reports/getNote.php?paperID=" + paperID + "&userID=" + userID;

  $("#noteMsg").load(dataSource, function(responseTxt, statusTxt, xhr) {
    if (statusTxt == "success") {
      $("#noteDiv").show();
      $("#noteDiv").css('left', currentX + scrOfX + 16 + 'px');

      top_pos = currentY+scrOfY-16;
      if (top_pos > ($(window).height() + scrOfY - 130)) {
        top_pos = $(window).height() + scrOfY - 130;
      }
      $("#noteDiv").css('top', top_pos + 'px');
    }
  });
  e.stopPropagation();
}

function viewAccessibility(userID, e) {
  $('#menudiv').hide();
  $("#noteDiv").hide();
  if (!e) var e = window.event;
  var currentX = e.clientX;
  var currentY = e.clientY;
  var scrOfX = $(document).scrollLeft();
  var scrOfY = $(document).scrollTop();

  dataSource = "../ajax/reports/getAccessibility.php?userID=" + userID;

  $("#accessMsg").load(dataSource, function(responseTxt, statusTxt, xhr) {
    if (statusTxt == "success") {
      $("#accessDiv").show();
      $("#accessDiv").css('left', currentX + scrOfX + 16 + 'px');

      top_pos = currentY+scrOfY-16;
      if (top_pos > ($(window).height() + scrOfY - 130)) {
        top_pos = $(window).height() + scrOfY - 130;
      }
      $("#accessDiv").css('top', top_pos + 'px');
    }
  });
  e.stopPropagation();
}

function viewToiletBreak(breakID, e) {
  $('#menudiv').hide();
  $('#noteDiv').hide();
  $('#accessDiv').hide();
  if (!e) var e = window.event;
  var currentX = e.clientX;
  var currentY = e.clientY;
  var scrOfX = $(document).scrollLeft();
  var scrOfY = $(document).scrollTop();

  dataSource = "../ajax/reports/getToiletBreak.php?breakID=" + breakID;

  $("#toiletMsg").load(dataSource, function(responseTxt, statusTxt, xhr) {
    if (statusTxt == "success") {
      $("#toiletDiv").show();
      $("#toiletDiv").css('left', currentX + scrOfX + 16 + 'px');

      top_pos = currentY+scrOfY-16;
      if (top_pos > ($(window).height() + scrOfY - 130)) {
        top_pos = $(window).height() + scrOfY - 130;
      }
      $("#toiletDiv").css('top', top_pos + 'px');
    }
  });
  e.stopPropagation();
}

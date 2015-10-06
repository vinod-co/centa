$(function () {
  $.ajaxSetup({ timeout: 3000 });
  $('#content').ajaxError(function (event, jqXHR, ajaxSettings, thrownError) {
   doError();
  });

  $('#save_message').hide();
  $('#save_fail_message').hide();

  $('.tbmark').click(updateMark);
});

var id, action;

function updateMark(e) {
  e.preventDefault();

  id = $(this).data('id');
  action = $(this).attr('id');

  var group = $(this).closest('.student-answer-block');
  var reminders = new Array();

  group.find('.reminder:checked').each(function() {
    reminders.push($(this).val());
  });
  reminders = reminders.join('|')

  var mark = $('#mark' + id).val();
  var comment = $('#comment' + id).val();

  $.post('../ajax/reports/save_textbox_marks.php',
    {
      paper_id: $('#paper_id').val(),
      q_id: $('#q_id').val(),
      log_id: $('#logrec' + id).val(),
      marker_id: $('#marker_id').val(),
      mark: mark,
      phase: $('#phase').val(),
      log: $('#log' + id).val(),
      user_id: $('#username' + id).val(),
      comments: comment,
      reminders: reminders
    },
    doSuccess
  ).fail(doError);
}

function doSuccess(data) {
  if (data != 'OK') {
    $('#save_fail_message').show(); 
    alert(langStrings['saveerror']);
    return false;
  } else {
    $('#save_fail_message').hide(); 
    $('#save_message').show().delay( 800 ).slideUp('slow'); 
  }

  if ($('#mark' + id).val() == 'NULL') {
    $('#ans_' + id).closest('.student-answer-block').removeClass('marked');
  } else {
    $('#ans_' + id).closest('.student-answer-block').addClass('marked');
  }

  if (action.indexOf('next') > -1) {
    $('#ans_' + id).closest('.student-answer-block').hide();
    $('#ans_' + (++id)).closest('.student-answer-block').show();
  } else if (action.indexOf('prev') > -1) {
    $('#ans_' + id).closest('.student-answer-block').hide();
    $('#ans_' + (--id)).closest('.student-answer-block').show();
  } else if (action.indexOf('finish') > -1) {
    params = getURLParams();
    phase = params.phase;
    startdate = params.startdate;
    enddate = params.enddate;
    paperid = params.paperID;
    repcourse = params.repcourse;
    baseparams = 'action=mark&repmodule=&repcourse=%&sortby=name&module=&folder=&percent=100&absent=0&studentsonly=1&ordering=asc' +
            '&meta1=First%20names=%&meta2=Forename=%&meta3=Group=%&meta4=Seminar%20Group=%&meta5=surname=%&meta6=Tutor%20Group=%&phase=';
    baseurl = '../reports/textbox_select_q.php?';
    extraParams = phase + '&repcourse=' + repcourse + '&startdate=' + startdate + '&enddate=' + enddate + '&paperID=' + paperid;
    textbox_select_q_url = baseurl + baseparams + extraParams;
    window.location.replace(textbox_select_q_url);
  }
}

function doError() {
  $('#save_fail_message').show(); 
  alert(langStrings['saveerror']);
}

/**
 * Get an array of URL parameters
 * @returns {Array} URL parameter name (array key) and value.
 */
function getURLParams() {

    var pageURL = window.location.search.substring(1);
    var params = pageURL.split('&');
    var paramsArray = [];

    for (var i = 0; i < params.length; i++) {
        var param = params[i].split('=');

        paramName = param[0];
        paramValue = param[1];
        paramsArray[paramName] = paramValue;
    }
    return paramsArray;
}

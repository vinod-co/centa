var occurrance;
var commentMatches;
var terms;

var getCheckRow = function () {
  var collapse = $('#collapse').is(':checked');
  var regexpMod = ($('#casesensitive').is(':checked')) ? 'g' : 'gi';
  terms = $('#keywords').val();

  return function () {
    if (terms != '') {
      var term = terms.split(' and ');
      term = term.join('|');

      var regexp = new RegExp('(' + term + ')', regexpMod); 
      var content = $(this).html();

      var matches = content.match(regexp);

      if (matches || !collapse) {
        if (!$(this).is(':visible')) {
          $(this).slideDown();
        }
      }

      if (matches) {
        $(this).html(content.replace(regexp, '<span class="highlight">$1</span>'));
      } else if (collapse) {
        $(this).slideUp();
      }

      if (matches) {
        occurrance += matches.length;
        commentMatches++;
      }
    } else {
      if (!$(this).is(':visible')) {
        $(this).slideDown();
      }
    }
  }
}

var cleanResponses = function () {
  $('li.response').each(function () {
    var content = $(this).html();
    var newcontent = content.replace(/<span class="highlight">([a-zA-Z]*)<\/span>/g, '$1');
    // IE8 - Grrrrr
    newcontent = newcontent.replace(/<SPAN class=highlight>([a-zA-Z]*)<\/SPAN>/g, '$1');
    $(this).html(newcontent);
  });
}

$(function () {
  $('#highlight').click(function (e) {
    e.preventDefault();
    cleanResponses();
    $('ul.response-list').each(function () {
      var checkRow = getCheckRow();
      occurrance = 0;
      commentMatches = 0;
      $(this).children('li.response').each(checkRow);

      if (terms != '') {
        $(this).next('div.comments').html(commentsStringMatches.replace(/%d/, occurrance).replace(/%s/, terms).replace(/%d/, commentMatches));
      } else {
        $(this).next('div.comments').html(commentsString.replace(/%d/, $(this).children('li.response').length));
      }
    });
  })
});
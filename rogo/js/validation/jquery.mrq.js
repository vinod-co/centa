$(function () {
  var button = null;
  ($('.submit').focus(function () { button = $(this).attr('id'); }))
  $('#edit_form').submit(function (e) {
    tinyMCE.triggerSave();
    var checked = 0;
    if (button == 'addbank' || button == 'addpaper' || button == 'submit-save') {
      $('.mrq-correct').each(function () {
        if ($(this).is(':checked')) {
          checked++;
        }
      });
      if (checked == 1 && confirm(lang['mrqconvert'])) {
        $('#mcqconvert').val('1');
      }
    }
  });
  $('#edit_form').validate({
    ignore: '',
    rules: {
      leadin: 'required',
      option_text1: {
        required: {
          depends: function (element) {
            return ($('#option_media1').val() == '' && ($('#existing_media1').length ==0 || $('#existing_media1').val() == ''));
          }
        }
      },
      option_text2: {
        required: {
          depends: function (element) {
            return ($('#option_media2').val() == '' && ($('#existing_media2').length ==0 || $('#existing_media2').val() == ''));
          }
        }
      },
      option_text3: {
        required: {
          depends: function (element) {
            return ($('#option_media3').val() == '' && ($('#existing_media3').length ==0 || $('#existing_media3').val() == ''));
          }
        }
      }
    },
    messages: {
      leadin: lang['enterleadin'],
      option_text1: '<br />'+lang['enteroption'],
      option_text2: '<br />'+lang['enteroption'],
      option_text3: '<br />'+lang['enteroption']
    },
    errorPlacement: function(error, element) {
      if (element.attr('name') == 'leadin') {
        error.insertAfter('#leadin_parent');
				
				$('#leadin_parent.defaultSkin table.mceLayout').css({'border-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout').css({'box-shadow' : '0 0 6px rgba(200, 0, 0, 0.85)'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceFirst td').css({'border-top-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceLast td').css({'border-bottom-color' : '#C00000'});
      } else if(element.attr('name') == 'option_text1') {
        error.insertAfter('#option_media1');
      } else if(element.attr('name') == 'option_text2') {
        error.insertAfter('#option_media2');
      } else if(element.attr('name') == 'option_text3') {
        error.insertAfter('#option_media3');
      } else {
        error.insertAfter(element);
      }
    },
    invalidHandler: function() {
      alert(lang['validationerror']);
    }
  });
});
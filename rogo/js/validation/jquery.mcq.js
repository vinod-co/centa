$(function() {
  $('#edit_form').submit(function () {
    tinyMCE.triggerSave();
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
      }
    },
    messages: {
      leadin: lang['enterleadin'],
      option_text1: '<br />'+lang['enteroption'],
      option_text2: '<br />'+lang['enteroption']
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
      } else {
        error.insertAfter(element);
      }
    },
    invalidHandler: function() {
      alert(lang['validationerror']);
    }
  });
})
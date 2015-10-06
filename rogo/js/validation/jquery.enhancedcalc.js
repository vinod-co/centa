$(function() {
  $('#edit_form').submit(function () { 
    tinyMCE.triggerSave();
  });

  $('#edit_form').validate({
    ignore: '',
    rules: {
      leadin: 'required',
      option_min1: 'required',
      option_max1: 'required',
      option_increment1: 'required',
      option_formula1: {
        required: function () {
          var haveFormula = true;
          $('.formula').each(function () {
            if ($(this).val() != '') {
              haveFormula = false;
            }
          });
          return haveFormula;
        }
      },
      option_increment2: {
        required: {
          depends: function (element) {
            return requiresIncrement(2);
          }
        }
      },
      option_increment3: {
        required: {
          depends: function (element) {
            return requiresIncrement(3);
          }
        }
      },
      option_increment4: {
        required: {
          depends: function (element) {
            return requiresIncrement(4);
          }
        }
      },
      option_increment5: {
        required: {
          depends: function (element) {
            return requiresIncrement(5);
          }
        }
      },
      option_increment6: {
        required: {
          depends: function (element) {
            return requiresIncrement(6);
          }
        }
      },
      option_increment7: {
        required: {
          depends: function (element) {
            return requiresIncrement(7);
          }
        }
      },
      option_increment8: {
        required: {
          depends: function (element) {
            return requiresIncrement(8);
          }
        }
      },
      option_increment9: {
        required: {
          depends: function (element) {
            return requiresIncrement(9);
          }
        }
      },
      option_increment10: {
        required: {
          depends: function (element) {
            return requiresIncrement(10);
          }
        }
      }
    },
    messages: {
      leadin: lang['enterleadin'],
      option_formula1: lang['enterformula'],
      option_increment1: '<br />' + lang['enteroptionshort'],
      option_increment2: '<br />' + lang['enteroptionshort'],
      option_increment3: '<br />' + lang['enteroptionshort'],
      option_increment4: '<br />' + lang['enteroptionshort'],
      option_increment5: '<br />' + lang['enteroptionshort'],
      option_increment6: '<br />' + lang['enteroptionshort'],
      option_increment7: '<br />' + lang['enteroptionshort'],
      option_increment8: '<br />' + lang['enteroptionshort'],
      option_increment9: '<br />' + lang['enteroptionshort'],
      option_increment10: '<br />' + lang['enteroptionshort']
    },
    errorPlacement: function(error, element) {
      if (element.attr('name') == 'leadin') {
        error.insertAfter('#leadin_parent');
				
				$('#leadin_parent.defaultSkin table.mceLayout').css({'border-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout').css({'box-shadow' : '0 0 6px rgba(200, 0, 0, 0.85)'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceFirst td').css({'border-top-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceLast td').css({'border-bottom-color' : '#C00000'});
      } else if (element.attr('name') == 'option_formula1') {
        error.insertBefore('#option_formula1');
      } else {
        error.insertAfter(element);
      }
    },
    invalidHandler: function() {
      alert(lang['validationerror']);
    }
  });
});

function requiresIncrement(index) {
  var rval = false;
  var min_value = $('#option_min' + index).val();
  if (min_value != '' && min_value.substring(0, 3) != 'var' && min_value.substring(0, 3) != 'ans') {
    rval = true;
  }
  return rval;
}
$(function() {
  $('#edit_form').submit(function () { 
    tinyMCE.triggerSave();
  })
  $('#edit_form').validate({
    ignore: '',
    rules: {
      scenario: 'required'
    },
    messages: {
      scenario: lang['entervignette']
    },
    errorPlacement: function(error, element) {
      if (element.attr('name') == 'scenario') {
        error.insertAfter('#scenario_parent');
				
				$('#scenario_parent.defaultSkin table.mceLayout').css({'border-color' : '#C00000'});
				$('#scenario_parent.defaultSkin table.mceLayout').css({'box-shadow' : '0 0 6px rgba(200, 0, 0, 0.85)'});
				$('#scenario_parent.defaultSkin table.mceLayout tr.mceFirst td').css({'border-top-color' : '#C00000'});
				$('#scenario_parent.defaultSkin table.mceLayout tr.mceLast td').css({'border-bottom-color' : '#C00000'});
      } else {
        error.insertAfter(element);
      }
    },
    invalidHandler: function() {
      alert(lang['validationerror']);
    }
  });
});
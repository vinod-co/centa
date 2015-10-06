$(function() {
  $('#edit_form').submit(function () { 
    tinyMCE.triggerSave();
  })
  $('#edit_form').validate({
    ignore: '',
    rules: {
      leadin: 'required'
    },
    messages: {
      leadin: lang['enterleadin'],
      //niko leadin: 'Please enter a leadin for the question'
    },
    errorPlacement: function(error, element) {
      if (element.attr('name') == 'leadin') {
        error.insertAfter('#leadin_parent');
				
				$('#leadin_parent.defaultSkin table.mceLayout').css({'border-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout').css({'box-shadow' : '0 0 6px rgba(200, 0, 0, 0.85)'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceFirst td').css({'border-top-color' : '#C00000'});
				$('#leadin_parent.defaultSkin table.mceLayout tr.mceLast td').css({'border-bottom-color' : '#C00000'});
      } else {
        error.insertAfter(element);
      }
    },
    invalidHandler: function() {
      //niko alert('There were problems with your submission. Please review the form and re-try');
      alert(lang['validationerror']);
    }
  });
})
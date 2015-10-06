function sendTextToAS3(lang, q_no, mode, image, correct, user){
  var toSend = lang + ';' + q_no + ';' + mode + ';' + image + ';';
  if (typeof 'correct' != 'undefined' && correct != '' && correct != undefined) {
    toSend += correct + ';';
  }
  if (typeof 'user' != 'undefined' && user != '' && user != undefined) {
    toSend += user + ';';
  }
  // Add small delay to get around race condition that was evident in Firefox
  setTimeout(function() {
    doSend(q_no, toSend);
  }, 1200)
}

function doSend(q_no, toSend) {
  var flash_fail = false;
	
	try
  {
    var flash1 = document.getElementById("externalinterface" + q_no + "_1");
		//console.log(flash1,toSend);
		flash_fail = true;
    if (flash1 && typeof(flash1.sendTextFromJS)!='undefined') {
      flash1.sendTextFromJS(toSend);
			flash_fail = false;
    }
  }
  catch(error)
  {
		flash_fail = true;
  }
	
	if (flash_fail) {
		try
		{
			var flash2 = document.getElementById("externalinterface" + q_no + "_2");
			if (flash2) {
				flash2.sendTextFromJS(toSend);
			}
		}
		catch(error)
		{
			flash_fail = true;
		}
	}
}

function receiveTextFromAS3(txt) {
  parts = txt.split(";");
  
  flashTargeta = parts.shift();
  data = parts.join(";");

  $('#' + flashTargeta).val(data);
}

function dateCopy() {
  var highlight = '';
  switch($(this).attr('id')) {
    case "fday":
      $("#tday").val($("#fday").val());
      highlight = 'tday';
      break;
    case "fmonth":
      $("#tmonth").val($("#fmonth").val());
      highlight = 'tmonth';
      break;
    case "fyear":
      $("#tyear").val($("#fyear").val());
      highlight = 'tyear';
      break;
    case "fhour":
      if ($("#fhour").val() > $("#thour").val()) {
        $("#thour").val($("#fhour").val());
        highlight = 'thour';
				if ($("#fminute").val() > $("#tminute").val()) {
					$("#tminute").val($("#fminute").val());
				}
      }
      break;
    case "fminute":
      if ($("#fminute").val() > $("#tminute").val() && $("#fhour").val() >= $("#thour").val()) {
        $("#tminute").val($("#fminute").val());
        highlight = 'tminute';
      }
      break;
    case "tday":
      $("#fday").val($("#tday").val());
      highlight = 'fday';
			break;
    case "tmonth":
      $("#fmonth").val($("#tmonth").val());
      highlight = 'fmonth';
      break;
    case "tyear":
      $("#fyear").val($("#tyear").val());
      highlight = 'fyear';
      break;
    case "thour":
      if ($("#thour").val() < $("#fhour").val()) {
        $("#fhour").val($("#thour").val());
        highlight = 'fhour';
      }
      break;
    case "tminute":
      if ($("#tminute").val() < $("#fminute").val() && $("#fhour").val() >= $("#thour").val()) {
        $("#fminute").val($("#tminute").val());
        highlight = 'fminute';
      }
      break;
  }
  if (highlight != '') {
    $('#' + highlight).effect("highlight", {}, 1500);
  }
}

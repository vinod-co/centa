$(function () {
  $(document).click(function() {
    $('#toprightmenu').fadeOut();
  });
  
  $('#toprightmenu_icon').click(function() {
		if ($('#toprightmenu').is(':visible')) {
			$('#toprightmenu').fadeOut();
		} else {
			$('#toprightmenu').fadeIn();
		}
		return false;
	});
	
	$('.header').click(function() {
		if ($('#toprightmenu').is(':visible')) {
			$('#toprightmenu').fadeOut();
		}
	});
	
	$('#admintools').click(function() {
		$('#toprightmenu').hide();
	  location.href = cfgRootPath + '/admin/index.php';
	});

	$('#signout').click(function() {
		$('#toprightmenu').hide();
	  location.href = cfgRootPath + '/logout.php';
	});

	$('#displaycredits').click(function() {
		$('#toprightmenu').hide();
		opencredits();
	});
	
	$('#aboutrogo').click(function() {
		$('#toprightmenu').hide();
		opencredits();
	});
	

	function opencredits() {
		notice=window.open(cfgRootPath + "/credits/index.php","credits","width=696,innerwidth=708,height=510,innerheight=560,scrollbars=no,resizable=no,toolbar=no,location=no,directories=no,status=0,menubar=0");
		notice.moveTo(screen.width/2-350,screen.height/2-255)
		if (window.focus) {
			notice.focus();
		}
	}
});

function toprightmenu_launchHelp($helpID) {
	$('#toprightmenu').hide();
	launchHelp($helpID);
}

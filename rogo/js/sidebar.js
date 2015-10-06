var scrollLine = 0;
var scrollDown = 0;
var myUpInterval = 0;
var myDownInterval = 0;
if (typeof cfgRootPath == 'undefined') var cfgRootPath = '';

function scrollUpStart(submenuID, arrayID, urlID, arrayName) {
	myUpInterval = window.setInterval(function () {
		if (scrollLine > 0) {
			scrollLine--;
			var limit = (scrollLine + 19);
			if (limit >= arrayID.length) {
				limit = arrayID.length-1;
			}
			var line = 0;
			for (i=scrollLine; i<=limit; i++) {
				submenuItemID = submenuID.substr(5,1) + '_' + line;
				if (urlID[i].substr(0,1) == '-') {
					$('#' + submenuItemID).html('<hr nonshade="nonshade" style="height:1px; border:none; background-color:#C0C0C0; color:#C0C0C0" />');
					$('#' + submenuItemID).attr('onclick', "window.location=''");
				} else if (urlID[i].substr(0,1) == '#') {
					$('#' + submenuItemID).html(urlID[i].substr(1));
				} else {
					$('#' + submenuItemID).html(arrayID[i]);
					$('#' + submenuItemID).attr('onclick', "window.location='" + urlID[i] + "'");
				}
				line++;
			}
			downID = submenuID.substr(5,1) + '_down';
			$('#' + downID).html('<img src="' + cfgRootPath + '/artwork/submenu_down_on.png" width="9" height="5" alt="down" />&nbsp;');
		} else {
			upID = submenuID.substr(5,1) + '_up';
			$('#' + upID).html('<img src="' + cfgRootPath + '/artwork/submenu_up_off.png" width="9" height="5" alt="up" />&nbsp;');
			clearInterval(myDownInterval);
		}
	},50);
}

function scrollUpEnd() {
	clearInterval(myUpInterval);
}

function scrollDownStart(submenuID, arrayID, urlID, arrayName) {
	myDownInterval = window.setInterval(function () {
		if (scrollLine < (arrayID.length-20)) {
			if (scrollLine == 0) {
				upID = submenuID.substr(5,1) + '_up';
				$('#' + upID).html('<img src="' + cfgRootPath + '/artwork/submenu_up_on.png" width="9" height="5" alt="up" />&nbsp;');
			}
			scrollLine++;
			var limit = (scrollLine + 19);
			if (limit >= arrayID.length) {
				limit = arrayID.length-1;
			}
			var line = 0;
			for (i=scrollLine; i<=limit; i++) {
				submenuItemID = submenuID.substr(5,1) + '_' + line;
				if (urlID[i].substr(0,1) == '-') {
					$('#' + submenuItemID).html('<hr nonshade="nonshade" style="height:1px; border:none; background-color:#C0C0C0; color:#C0C0C0" />');
					$('#' + submenuItemID).attr('onclick', "window.location=''");
				} else if (urlID[i].substr(0,1) == '#') {
					$('#' + submenuItemID).html(urlID[i].substr(1));
				} else {
					$('#' + submenuItemID).html(arrayID[i]);
					$('#' + submenuItemID).attr('onclick', "window.location='" + urlID[i] + "'");
				}
				line++;
			}
		} else {
			downID = submenuID.substr(5,1) + '_down';
			$('#' + downID).html('<img src="' + cfgRootPath + '/artwork/submenu_down_off.png" width="9" height="5" alt="down" />&nbsp;');
			clearInterval(myDownInterval);
		}
	},50);
}

function scrollDownEnd() {
	clearInterval(myDownInterval);
}

function showMenu(submenuID, menuID, callingID, arrayID, urlID, e) { 
  $('#popup').hide();
	scrollLine = 0;

	var limit = (scrollLine + 19);
	if (limit >= arrayID.length) {
		limit = arrayID.length-1;
	}
	if (arrayID.length > 20) {
		upID = submenuID.substr(5,1) + '_up';
		$('#' + upID).html('<img src="' + cfgRootPath + '/artwork/submenu_up_off.png" width="9" height="5" alt="up" />&nbsp;');
		downID = submenuID.substr(5,1) + '_down';
		$('#' + downID).html('<img src="' + cfgRootPath + '/artwork/submenu_down_on.png" width="9" height="5" alt="down" />&nbsp;');
	}
	var line = 0;
	for (i=scrollLine;i<=limit;i++) {
		submenuItemID = submenuID.substr(5,1) + '_' + line;
		if (urlID[i].substr(0,1) == '-') {
			$('#' + submenuItemID).html('<hr nonshade="nonshade" style="height:1px; border:none; background-color:#C0C0C0; color:#C0C0C0" />');
			$('#' + submenuItemID).attr('onclick', "window.location=''");
		} else if (urlID[i].substr(0,1) == '#') {
			$('#' + submenuItemID).html(urlID[i].substr(1));
		} else {
			$('#' + submenuItemID).html(arrayID[i]);
			$('#' + submenuItemID).attr('onclick', "window.location='" + urlID[i] + "'");
		}
		line++;
	}

	if (!e) var e = window.event;
	if ($('#' + submenuID).css('display') != 'block') {
		hideMenus(e);
		$('#' + submenuID).show();
	} else {
		hideMenus(e);
	}
	popupHeight = $('#' + submenuID).height();
	
	sidebarHeight = $('#left-sidebar').height();
	
	mytop = $('#' + callingID).offset().top - $(document).scrollTop();
	if ((mytop + popupHeight) > sidebarHeight) {
		mytop = sidebarHeight - popupHeight - 6;
	}
	$('#' + submenuID).css('top', mytop + 'px');
	
	e.cancelBubble = true;
	
	return false;
}

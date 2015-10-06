// JavaScript Document
// This file is part of Rogo
//
// Rogo is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogo is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogo.  If not, see <http://www.gnu.org/licenses/>.

//
//
// Module page helper functions
//
// @author Joseph Bater
// @version 1.0
// @copyright Copyright (c) 2015 The University of Nottingham
// @package
//

function newPaper(module) {
    notice = window.open("../paper/new_paper1.php?module=" + module,"paper","width=700,height=500,left="+(screen.width/2-325)+",top="+(screen.height/2-250)+",scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    if (window.focus) {
      notice.focus();
    }
}

function newQuestion(module) {
    notice = window.open("../question/new.php?module=" + module,"question","width=800,height=500,left="+(screen.width/2-400)+",top="+(screen.height/2-250)+",scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable");
    if (window.focus) {
        notice.focus();
    }
}

function resizeList() {
    var offset = $('#list').offset();
    winH = ($(window).height() - offset.top) - 2;

    $('#list').css('height', winH + 'px');
}

$(function () {
    resizeList();

    $(window).resize(function(){
        resizeList();
    });

    $(document).click(function() {
        hideMenus();
    });

});

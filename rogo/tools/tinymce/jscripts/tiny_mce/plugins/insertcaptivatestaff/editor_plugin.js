(function(){tinymce.PluginManager.requireLangPack('insertcaptivatestaff');tinymce.create('tinymce.plugins.insertcaptivatestaffPlugin',{init:function(ed,url){ed.addCommand('mceinsertcaptivatestaff',function(){ed.windowManager.open({file:url+'/dialog.php',width:320+parseInt(ed.getLang('insertcaptivatestaff.delta_width',0)),height:120+parseInt(ed.getLang('insertcaptivatestaff.delta_height',0)),inline:1},{plugin_url:url,some_custom_arg:'custom arg'})});ed.addButton('insertcaptivatestaff',{title:'insertcaptivatestaff.desc',cmd:'mceinsertcaptivatestaff',image:url+'/img/captivate.gif'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('insertcaptivatestaff',n.nodeName=='IMG')})},createControl:function(n,cm){return null},getInfo:function(){return{longname:'insertcaptivatestaff plugin',author:'Some author',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/insertcaptivatestaff',version:"1.0"}}});tinymce.PluginManager.add('insertcaptivatestaff',tinymce.plugins.insertcaptivatestaffPlugin)})();
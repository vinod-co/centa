(function(){tinymce.PluginManager.requireLangPack('insertcaptivatestudent');tinymce.create('tinymce.plugins.insertcaptivatestudentPlugin',{init:function(ed,url){ed.addCommand('mceinsertcaptivatestudent',function(){ed.windowManager.open({file:url+'/dialog.php',width:320+parseInt(ed.getLang('insertcaptivatestudent.delta_width',0)),height:120+parseInt(ed.getLang('insertcaptivatestudent.delta_height',0)),inline:1},{plugin_url:url,some_custom_arg:'custom arg'})});ed.addButton('insertcaptivatestudent',{title:'insertcaptivatestudent.desc',cmd:'mceinsertcaptivatestudent',image:url+'/img/captivate.gif'});ed.onNodeChange.add(function(ed,cm,n){cm.setActive('insertcaptivatestudent',n.nodeName=='IMG')})},createControl:function(n,cm){return null},getInfo:function(){return{longname:'insertcaptivatestudent plugin',author:'Some author',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/insertcaptivatestudent',version:"1.0"}}});tinymce.PluginManager.add('insertcaptivatestudent',tinymce.plugins.insertcaptivatestudentPlugin)})();
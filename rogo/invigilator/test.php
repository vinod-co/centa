<?php
//require '../include/sysadmin_auth.inc';
require_once '../include/invigilator_auth.inc';
?>
<html>
<head>
<title>Test</title>

<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
<script>
var timerId = setInterval(timerMethod, 1000);

function timerMethod() {
  $.get("check_exam_announcements.php", {paperID:"4096"}, function(data){
     $('#msg').html(data);
   });
}

</script>
</head>

<body>
<div id="msg"></div>
</body>
</html>
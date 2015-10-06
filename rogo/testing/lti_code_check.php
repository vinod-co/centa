<?php

require_once '../include/staff_student_auth.inc';
require_once '../classes/lti_integration.class.php';

$lti_i = lti_integration::load();

echo "use modcode as get parameter<br>";

print $_GET['modcode'] . '<BR>';

var_dump($lti_i::module_code_translate($_GET['modcode'],'TITLE DFDGDS'));

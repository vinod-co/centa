<?php
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

//HTML5 part
require_once '../lang/' . $language . '/question/edit/hotspot_correct.txt';
require_once '../lang/' . $language . '/question/edit/area.txt';
require_once '../lang/' . $language . '/paper/hotspot_answer.txt';
require_once '../lang/' . $language . '/paper/hotspot_question.txt';
require_once '../lang/' . $language . '/paper/label_answer.txt';
$jstring = $string; //to pass it to JavaScript HTML5 modules
//HTML5 part

require_once '../lang/' . $language . '/include/months.inc';
require_once '../lang/' . $language . '/question/sct_shared.php';
require_once '../lang/' . $language . '/include/paper_security.inc';

$string['survey'] = 'Survey';
$string['assessment'] = 'Assessment';
$string['finish'] = 'Finish';
$string['screen'] = 'Screen';
$string['clarificationscreen'] = 'Screen %s of %s';
$string['mark'] = 'mark';
$string['marks'] = 'marks';
$string['note'] = 'Note';
$string['true'] = 'True';
$string['false'] = 'False';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['abstain'] = 'Abstain';
$string['na'] = 'N/A';
$string['other'] = 'Other';
$string['unanswered'] = 'Unanswered';
$string['unansweredquestion'] = '= unanswered question';
$string['negmarking'] = 'negative marking';
$string['bonusmark'] = 'for correct options, plus %d bonus %s for fully correct order';
$string['calculator'] = 'Calculator';
$string['timeremaining'] = 'Time remaining';
$string['finishnote'] = 'Complete all questions before clicking &#145;Finish&#146;, you will not be able to go back.';
$string['gobackpink'] = 'When you go back unanswered questions will be highlighted in pink.';
$string['fireexit'] = 'Fire Exit';
$string['pleasecomplete'] = 'Complete all questions before clicking &#145;Screen %d &gt;&#146;, you will not be able to go back.';
$string['javacheck1'] = 'Have you completed all the questions on this screen, you will NOT be able to go back.<br /><br /><strong>Are you sure you wish to continue?</strong>';
$string['javacheck2'] = "Are you sure you wish to finish?<br /><br /><strong>After clicking 'OK' you will not be able to go back.</strong>";
$string['error_random'] = '<strong>ERROR:</strong> Unable to find unique question for random question block.';
$string['error_keywords'] = '<strong>ERROR:</strong> Unable to find unique question for supplied keywords.';
$string['error_paper'] = 'The requested paper cannot be found.';
$string['error_qtype'] = 'No question type defined.';
$string['holddownctrlkey'] = '(Hold down &lt;CTRL&gt; key, then click mouse to toggle options on/off)';
$string['msgselectable1'] = 'Too many options selected!\n\nOnly';
$string['msgselectable2'] = 'items can be selected in this question.';
$string['msgselectable3'] = 'You have already selected';
$string['msgselectable4'] = '.\n\nPlease select a different ranking.';
//ajax saving and auto saving messages
$string['saving'] = 'Saving';
$string['auto_saving'] = 'Auto saving ...';
$string['auto_ok'] = 'Auto Save Successful';
$string['savefailed'] = 'Save Failed!';
$string['tryagain'] = 'Please try again, by moving to the next or previous screens.';
$string['questionclarification'] = 'Question Clarification';
$string['question'] = 'Question';
$string['answer_to'] = 'answer to';
$string['decimal_places'] = 'decimal places';
$string['significant_figures'] = 'significant figures';
$string['forcesave'] = 'Your time has expired and your answers have been saved';
?>
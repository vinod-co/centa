<?php
// This file is part of Rogō
//
// Rogō is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Rogō is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Rogō.  If not, see <http://www.gnu.org/licenses/>.

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

$string['survey'] = 'Průzkum';
$string['assessment'] = 'Hodnocená zkouška';
$string['finish'] = 'Dokončit';
$string['screen'] = 'Obrazovka';
$string['clarificationscreen'] = 'Obrazovka %s z %s';
$string['mark'] = 'Hodnocení';
$string['marks'] = 'Hodnocení';
$string['note'] = 'Poznámka';
$string['true'] = 'Pravda';
$string['false'] = 'Nepravda';
$string['yes'] = 'Ano';
$string['no'] = 'Ne';
$string['abstain'] = 'Zdrželo se';
$string['na'] = 'Není k dispozici';
$string['unanswered'] = 'Nezodpovězeno';
$string['unansweredquestion'] = '= Nezodpovězená úloha';
$string['negmarking'] = 'Záporné známkování';
$string['bonusmark'] = 'Ke správným odpovědím přidat s %d bonusem %s zcela správné odpovědi';
$string['calculator'] = 'Kalkulačka';
$string['timeremaining'] = 'Zbývající čas';
$string['finishnote'] = '<strong>Poznámka:</strong> Před klepnutím na tlačítko &#145;Dokončit&#146; zodpovězte, prosím, veškeré úlohy, poté se již nebudete moci vrátit k jejich vypracování';
$string['gobackpink'] = 'Pokud se vrátíte, nezodpovězené úlohy budou růžově zvýrazněné.';
$string['fireexit'] = 'Nouzový východ';
$string['pleasecomplete'] = '<strong>Poznámka:</strong> Před klepnutím na tlačítko &#145;Dokončit&#146; zodpovězte, prosím, veškeré úlohy, poté se již nebudete moci vrátit k jejich vypracování';
$string['javacheck1'] = 'Dokončili jste všechny úkoly v tomto okně? Zpět jít nelze. .\nJste si skutečně jisti, že chcete pokračovat?';
$string['javacheck2'] = "Jste si skutečně jisti, že chcete skončit? Po kliknutí na tlačítko 'OK' se již nebudete moci vrátit.";
$string['error_random'] = 'Chyba: nelze nalézt úlohu jakékoli sestavy otázek';
$string['error_keywords'] = 'CHYBA: nelze najít unikátní úlohu ke klíčovému slovu';
$string['error_paper'] = 'Nelze nalézt požadovaný dokument.';
$string['error_qtype'] = 'Není nastaven Typ úlohy.';
$string['holddownctrlkey'] = '(Stiskněte klávesu &lt;CTRL&gt;a poté pro přepnutí mezi volbou zapnuto/vypnuto klikněte na tlačítko myši )';
$string['msgselectable1'] = 'Vybráno příliš mnoho voleb! Pouze \n\n';
$string['msgselectable2'] = 'Položky, které mohou být vybrány v této úloze.';
$string['msgselectable3'] = 'Již jste vybrali.';
$string['msgselectable4'] = '.\n\nVyberte, prosím, jiné místo.';
//ajax ukládací a autoukládací zpráva
$string['saving'] = 'Ukládání';
$string['auto_saving'] = 'Automatické ukládání...';
$string['auto_ok'] = 'Automatické ukládání dokončeno';
$string['savefailed'] = 'Ukládání selhalo';
$string['tryagain'] = 'Zkuste, prosím, znovu: Přechodem na předchozí nebo následující okno.</div>';

$string['question'] = 'Úloha';
$string['questionclarification'] = 'Ujasnění úlohy';
$string['other'] = 'Ostatní';
$string['answer_to'] = 'odpovědět';
$string['decimal_places'] = 'desetinná místa';
$string['significant_figures'] = 'významná figura';
$string['forcesave'] = 'Váš čas vypršel a Vaše odpovědi byly uloženy. ';
?>
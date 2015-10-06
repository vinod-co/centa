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

require '../lang/' . $language . '/include/paper_options.inc';
require 'shared.inc';
require '../lang/' . $language . '/question/edit/likert_scales.php';

$string['import'] = 'Importovat';
$string['import2'] = 'Importovat';
$string['importfromqti'] = 'Importovat z QTI';
$string['file'] = 'Soubor';
$string['qtiimporterror'] = 'Během importu Vašeho QTI souboru došlo k chybě.';
$string['qtiimported'] = 'Váš QTI soubor byl importován';
$string['questionproblems'] = 'Některé z Vašich úloh nebyly naimportovány správně.';
$string['hadproblemsimporting'] = '%d z %d úloh mělo potíže s importem.';
$string['importedquestions'] = 'Importováno %d úloh.';
$string['backtopaper'] = 'Zpět na dokument';
$string['errmsg1'] = 'Tento typ exportu není podporován';
$string['errmsg2'] = 'Tento typ importu není podporován';
$string['invalidxml'] = '%s je neplatný XML soubor';
$string['invalidzip'] = 'Nahrán neplatný Zip soubor';
//$string['invalidzip2'] = '%s je neplatným XML souborem';
$string['noqtiinzip'] = 'V Zip souboru nejsou žádné QTI XML soubory';
$string['qunsupported'] = 'Typ úlohy %s není podporován';
$string['noresponsegroups'] = 'Skupiny nejsou v současnosti podporovány.';
$string['norenderextensions'] = 'Render extensions nejsou v současnosti podporovány.';
$string['mrq1other'] = 'Vícenásobná odpověď - 1 známka za správnou odpověď';
$string['nomultiplecard'] = 'Všechny štítky jsou odlišné a mají více kardinalit, úloha není v Rogo podporována. &#333;.';
$string['labelsetserror'] = 'Sady štítků úlohy nejsou totožné, možná by bylo lepší toto importovat jako Prázdná rolovací políčka??';
$string['nomultiinputs'] = 'Úlohy s mnohačetnými numerickými vstupy nelze naimporotvat';
$string['blanktypeerror'] = 'Úloha doplňovacího typu postrádá rozbalovací nabídku či vkládaný text';
$string['addingsub'] = 'Přidání podbodů - render_fib bezdětný';
$string['posnocond'] = 'Pozitivní výsledek bez podmínky/stavu, nelze určit správnou odpověď.';
$string['multiplepos'] = 'Ve výsledku více pozitivních hodnot, správná odpověď může být špatně. ';
$string['multiposmultiopt'] = 'Vícero pozitivních výsledků s mnohočetnými možnostmi odpovědi, správná odpověď může být špatně.';
$string['nomatchinglabel'] = 'Nelze nalézt informace k Přiřazování štítků.';
$string['nolikertfeedback'] = 'Rog&#333; Komentář k Likertově stupnici se neuchovává, tudíž a je tudíž ztracen.';
$string['nocorrect'] = 'Nelze najít správnou odpověď';
$string['multipleconds'] = 'Nalezeno více podmínek hodnotících úlohu, ignorovat všechny, kromě první.';
$string['mrqnoismulti'] = 'Pokoušíte se načíst MRQ bez multisetu!';
$string['importingtext'] = 'Import textu úlohy s podmínkami hodnocení. Nebude automaticky oznámkovano. Rog&#333;';
$string['someneg'] = 'Několik negativit - 1 známka za každou správnou volbu s negativitou';
$string['noneg'] = 'Žádné negativity a několik pozitivit - 1 známka za každou správnou volbu';

$string['qtiimport'] = 'Importovat QTI ';
$string['imported1_2'] = 'Importováno ze souboru QTI 1.2';
$string['paperlocked'] = 'Dokument je uzamčen';
$string['paperlockedmsg'] = 'Tento dokument je v současnosti uzamčen a nelze jej upravovat.';

$string['loadingsection'] = 'Načítání sekce';
$string['loadingblank'] = 'Načítání prázdného řetězce';
$string['loadingblankdrop'] = 'Načítání prázdného rozevíracího seznamu ';
$string['fileoutput'] = 'Výstup do souboru ';

$string['type'] = 'Typ dokumentu';
?>
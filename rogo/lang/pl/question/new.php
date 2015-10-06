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

require $configObject->get('cfg_web_root') . 'lang/' . $language . '/include/question_types.inc';

$string['newquestion'] = 'Nowe pytanie';
$string['area_desc'] = 'Umożliwia studentowi zaznaczyć obszar jako swoją odpowiedź';
$string['enhancedcalc_desc'] = 'Odpowiedź liczbowa do pytań z losowanymi zmiennymi.';
$string['dichotomous_desc'] = 'Prezentacja zestawu pytań prawda-fałsz.';
$string['extmatch_desc'] = 'Prezentacja zestawu scenariuszy współdzielących zestaw opcji odpowiedzi.';
$string['blank_desc'] = 'Akapit tekstu z lukami, które student wypełnia.';
$string['info_desc'] = 'Nie pytanie jako takie, a informacja dla studenta na temat pozostałych pytań lub arkusza.';
$string['matrix_desc'] = 'Dopasowywanie odpowiedzi do pytań w układzie tabelarycznym.';
$string['hotspot_desc'] = 'Student ma kliknąć w odpowiednią część obrazka. W jednym pytaniu dostępnych może być wiele fragmentów.';
$string['labelling_desc'] = 'Student ma przeciągnąć etykiety w odpowiednie miejsca na obrazku.';
$string['likert_desc'] = 'Skala psychometryczna do zastosowania w ankietach.';
$string['mcq_desc'] = 'Wybieranie poprawnej opcji z wielu dostępnych.';
$string['mrq_desc'] = 'Wybieranie kilku poprawnzch opcji z wielu dostępnych.';
$string['keyword_based_desc'] = "Pytanie stanowiące pojemnik dla zbioru pytań źródłowych bazujących na określonym słowie kluczowym, z których losowo będzie wybierane jedno.";
$string['random_desc'] = "Pytanie stanowiące pojemnik dla zbioru pytań źródłowych, z których losowo będzie wybierane jedno.";
$string['rank_desc'] = 'Układanie zestawu opcji w prawidłowym porządku.';
$string['sct_desc'] = 'Pytania zaprojektowane do oceny umiejętności interpretacji danych klinicznych.';
$string['textbox_desc'] = 'Pola tekstowe przyjmujące szersze odpowiedzi studenta. Moga byc stosowane w ankietach i sprawdzianach. Odpowiedzi tekstowe muszą być oceniane ręcznie przez egzaminatora.';
$string['true_false_desc'] = 'Pojedyncze pytanie z odpowiedzią typu prawda/fałsz.';
?>
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

$string['newquestion'] = 'Nová Úloha';
$string['area_desc'] = 'Umožňuje studentům, jakožto jejich odpověď, vytyčit určitou plochu.';
$string['enhancedcalc_desc'] = 'Numerická odpověď založená na úloze s náhodnými proměnnými.';
$string['dichotomous_desc'] = 'Prezentace více úloh typu ano/ne..';
$string['extmatch_desc'] = 'Prezentace více scénářů sdílejících společnou sadu možností odpovědi.';
$string['blank_desc'] = 'Textový odstavec s vloženými prázdnými políčky, která student vyplní..';
$string['info_desc'] = 'Není úlohou jako takovou - avšak poskytuje studentovi informace, které mu pomohou se zbytkem testových úloh.';
$string['matrix_desc'] = 'V maticovém zobrazení k sobě přiřaďte otázky a odpovědi.';
$string['hotspot_desc'] = 'Student musí kliknout na správnou část obrázku. V jedné úloze může být 1 a více oblastí..';
$string['labelling_desc'] = 'Student musí  přetáhnout popisky ke správným zástupným symbolům na obrázku.';
$string['likert_desc'] = 'Psychometrické stupnice pro použití při Průzkumech.';
$string['mcq_desc'] = 'Vyber jednu variantu z mnoha.';
$string['mrq_desc'] = 'Vyber několik variant z mnoha.';
$string['keyword_based_desc'] = "Tato úloha je kontejnerem pro soubor \"zdrojových\" úloh, závisejících na zadaném klíčovém slově, z nichž jedna bude studentovi náhodně vybrána.";
$string['random_desc'] = "Tato úloha je kontejnerem pro soubor \"zdrojových\" úloh, z nichž jedna bude studentovi náhodně vybrána.";
$string['rank_desc'] = 'Ohodnoťte sadu seřazených možností.';
$string['sct_desc'] = 'Úlohy vyhodnocující dovednost interpretace klinických údajů.';
$string['textbox_desc'] = 'Textová pole zachycují studentovy odpovědi. Mohou být použita při výzkumech a hodnocení. Odpovědi v textových polích vyžadují ruční ohodnocení učitelem.';
$string['true_false_desc'] = 'Úloha, na kterou se odpovídá ano/ne.';
?>
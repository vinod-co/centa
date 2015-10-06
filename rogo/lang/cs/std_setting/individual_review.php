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

require '../lang/' . $language . '/std_setting/std_set_shared.php';
require '../lang/' . $language . '/paper/start.php';

$string['modifiedangoffmethod'] = 'Modifikovaná Angoffova metoda';
$string['ebelmethod'] = 'Ebelova metoda';
$string['modangoffstep1'] = 'Pomocí oranžového rozbalovacího seznamu, vedle každé úlohy, označte <strong>minimum</strong> studentů, jež očekáváte, že v dané kategorii, odpoví správně.';
$string['step1'] = '<strong>Krok 1:</strong><br />U každé úlohy nejprve pomocí oranžového rozbalovacího seznamu vyberte <strong>obtížnost</strong> úlohy (Snadná, Střední nebo Těžká) a poté přiřaďte k úlohám <strong>významnost</strong> (Zásadní, Důležitá nebo Okrajová).';
$string['step2'] = '<strong>Krok 2: Mřížka potřebné známky</strong><br />U každé kategorie (např. Snadná/Zásadní, Snadná/Důležitá, apod.) určete <strong>minimální</strong> počet kandidátů,jež očekáváte, že v dané kategorii, odpoví správně.';
$string['step3'] = '<strong>Krok 3: Mřízka S vyznamenáním</strong><br />U každé kategorie (např. Snadná/Zásadní, Snadná/Důležitá, apod.) určete <strong>minimální</strong> počet kandidátů,jež očekáváte, že v dané kategorii, odpoví správně.';
$string['gridbelow'] = 'Použijte mřížku níže';
$string['top20'] = 'Vyberte nejlepších 20%';
$string['donotapply'] = 'Nepoužívejte';
$string['easy'] = 'Jednoduchá';
$string['medium'] = 'Střední';
$string['hard'] = 'Nesnadná';
$string['essential'] = 'Zásadní';
$string['important'] = 'Důležitá';
$string['nicetoknow'] = 'Doplňková';
$string['papermarks'] = 'známky dokumentu';
$string['reviewmarks'] = 'přehled známek';
$string['cutscore'] = 'snížené skóre';
$string['saveexit'] = 'Uložit &amp; ukončit';
$string['savecontinue'] = 'Uložit &amp; pokračovat';
$string['savebank'] = 'Uložit body hodnocení do banky úloh';
$string['cannotbeused'] = '<strong>Poznámka:</strong> Ebelova metoda nemůže být použita u úloh typu standardní textové úlohy.';
$string['na'] = 'N/A';
$string['screen'] = 'Obrazovka';
$string['note'] = 'POZNÁMKA:';
$string['notpossibletostandard'] = 'Není možné u úloh typu Test shody se scénářem.';
$string['notvisible'] = '<strong>Information:</strong> (not visible to candidates)';
$string['reviewermsg'] = 'Toto je početní úloha. Proměnné jsou vypočteny on-line a budou se pro jednotlivé kandidáty lišit. Odpověď je však založena na jednom výrazu. Kandidáti nevidí <strong>$A</strong>, atd. uvidí pouze náhodně generovaná čísla.';
$string['variable'] = 'Proměnná';
$string['generated'] = 'Generováno';
$string['max'] = 'Maximum';
$string['min'] = 'Minimum';
$string['formula'] = 'Vzorec';
$string['tolerancefull'] = 'Odchylka pro celkové hodnocení';
$string['tolerancepartial'] = 'Odchylka pro částečné hodnocení';
$string['togglevariables'] = 'Toggle Variables';
?>